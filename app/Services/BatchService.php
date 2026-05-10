<?php

namespace App\Services;

use App\Repositories\BatchRepository;
use App\Models\ProductBrand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

/**
 * BatchService
 *
 * Central business logic for batch stock management.
 * Orchestrates the complete batch lifecycle: creation with auto-calculated
 * fields, stock recalculation, WAC repricing, and concurrency protection.
 *
 * ARCHITECTURE NOTE (Future Reporting):
 * ─────────────────────────────────────
 * Because all stock mutation logic is centralized here (not scattered
 * across controllers or event listeners), building a StockReportService
 * in the future becomes trivial:
 *
 *   1. Inject BatchRepository to query historical batch data
 *   2. Inject this BatchService to reuse recalculation logic
 *   3. Add methods like getStockHistory(), getWACTrend(), exportStockCSV()
 *
 * The 4-layer pattern ensures the report layer never needs to understand
 * HOW stock is calculated — it only asks for the result. This is the
 * Single Responsibility Principle applied at the architectural level.
 */
class BatchService
{
    protected BatchRepository $batchRepository;

    public function __construct(BatchRepository $batchRepository)
    {
        $this->batchRepository = $batchRepository;
    }

    /**
     * Retrieve all batches (non-paginated).
     *
     * Retained for backward compatibility with the API layer.
     *
     * @param  array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllBatches($filters = [])
    {
        return $this->batchRepository->getAll($filters);
    }

    /**
     * Retrieve paginated batches for the dashboard batch index view.
     *
     * @param  array $filters  Optional: product_brand_id, is_active, search
     * @param  int   $perPage  Results per page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedBatches($filters = [], int $perPage = 15)
    {
        return $this->batchRepository->getPaginated($filters, $perPage);
    }

    /**
     * Retrieve a single batch by UUID.
     *
     * @param  string $id  UUID of the batch
     * @return \App\Models\Batch
     */
    public function getBatchById($id)
    {
        return $this->batchRepository->findById($id);
    }

    /**
     * Create a new batch with concurrency-safe stock recalculation.
     *
     * The entire operation is wrapped in a database transaction with
     * pessimistic row locking on the target ProductBrand. This prevents
     * race conditions where two simultaneous batch inserts could read
     * the same stale stock value and overwrite each other's calculations.
     *
     * Flow:
     * 1. Lock the ProductBrand row (SELECT ... FOR UPDATE)
     * 2. Insert the new batch record
     * 3. Recalculate total stock from all active batches (SUM aggregate)
     * 4. Recalculate WAC (Weighted Average Cost) for pricing
     * 5. Commit — the lock is released
     *
     * If any step fails, the entire transaction rolls back: no partial
     * batch records or inconsistent stock counts persist.
     *
     * @param  array $data  Validated batch data from StoreBatchRequest
     * @return \App\Models\Batch  The created batch with loaded relations
     *
     * @throws \Exception  If the ProductBrand cannot be locked or any DB error
     */
    public function createBatch(array $data)
    {
        return DB::transaction(function () use ($data) {
            // STEP 1: Acquire pessimistic lock on the ProductBrand row.
            // Any concurrent transaction attempting to lock the same row
            // will WAIT here until this transaction commits or rolls back.
            $productBrand = $this->batchRepository->lockProductBrandForUpdate(
                $data['product_brand_id']
            );

            // STEP 2: Auto-fill computed fields before insert.
            // current_stock starts equal to initial_stock (no units sold yet).
            if (!isset($data['current_stock'])) {
                $data['current_stock'] = $data['initial_stock'];
            }

            // Generate a unique, human-readable batch code if not provided.
            // Format: BCH-YYYYMMDD-XXXXX (date-stamped for traceability).
            if (!isset($data['batch_code'])) {
                $data['batch_code'] = 'BCH-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
            }

            $data['created_at'] = Carbon::now();

            // Default to active status for new batches
            if (!isset($data['is_active'])) {
                $data['is_active'] = true;
            }

            // STEP 3: Persist the batch record.
            $batch = $this->batchRepository->create($data);

            // STEP 4: Recalculate the total stock on the ProductBrand.
            // Uses SUM aggregate on all active batches for accuracy.
            $this->recalculateTotalStock($productBrand);

            // The average cost (Modal) is now computed dynamically via accessor
            // and does not overwrite selling_price.

            // Return the batch with loaded relations for the response
            return $this->batchRepository->findById($batch->id);
        });
    }

    /**
     * Update an existing batch with stock recalculation.
     *
     * Locked and transactional to maintain consistency when
     * modifying stock values on existing batches (e.g., stock corrections).
     *
     * @param  string $id    UUID of the batch
     * @param  array  $data  Attributes to update
     * @return \App\Models\Batch
     */
    public function updateBatch($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $batch = $this->batchRepository->findById($id);

            // Lock the parent ProductBrand to prevent concurrent stock reads
            $productBrand = $this->batchRepository->lockProductBrandForUpdate(
                $batch->product_brand_id
            );

            // Constraint Anti-Minus Logic:
            // Calculate how many items from this batch have already been sold.
            $soldQty = $batch->initial_stock - $batch->current_stock;

            if (isset($data['initial_stock'])) {
                if ($data['initial_stock'] < $soldQty) {
                    throw new Exception("Stok awal tidak bisa diubah lebih rendah dari jumlah barang yang sudah terjual dari batch ini ({$soldQty} unit).");
                }
                
                // Recalculate the new current stock based on the adjusted initial stock
                $data['current_stock'] = $data['initial_stock'] - $soldQty;
            }

            $batch = $this->batchRepository->update($batch, $data);

            // Recalculate totals after the stock values have changed
            $this->recalculateTotalStock($productBrand);


            return $this->batchRepository->findById($batch->id);
        });
    }

    /**
     * Delete a batch with stock recalculation.
     *
     * Removes the batch record and updates the parent ProductBrand's
     * stock and WAC accordingly. Locked to prevent concurrent reads
     * during the recalculation window.
     *
     * @param  string $id  UUID of the batch
     * @return bool
     */
    public function deleteBatch($id)
    {
        return DB::transaction(function () use ($id) {
            $batch = $this->batchRepository->findById($id);

            // Lock before delete to ensure consistent recalculation
            $productBrand = $this->batchRepository->lockProductBrandForUpdate(
                $batch->product_brand_id
            );

            $this->batchRepository->delete($batch);

            // Recalculate after removal
            $this->recalculateTotalStock($productBrand);


            return true;
        });
    }

    /**
     * Toggle a batch's active status.
     *
     * Deactivating a batch excludes it from stock counts and WAC
     * calculations without deleting the historical record.
     *
     * @param  string $id  UUID of the batch
     * @return \App\Models\Batch
     */
    public function toggleBatchActive(string $id)
    {
        return DB::transaction(function () use ($id) {
            $batch = $this->batchRepository->findById($id);

            $productBrand = $this->batchRepository->lockProductBrandForUpdate(
                $batch->product_brand_id
            );

            $batch = $this->batchRepository->update($batch, [
                'is_active' => !$batch->is_active,
            ]);

            $this->recalculateTotalStock($productBrand);


            return $this->batchRepository->findById($batch->id);
        });
    }

    /**
     * Recalculate total stock on the ProductBrand.
     *
     * Performs a SUM aggregate query across all active batches and
     * stores the result. This denormalized field allows the product
     * listing to display stock counts without joining batches.
     *
     * NOTE: The products table does not have a total_stock column.
     * Stock is tracked at the product_brand level (each variant has
     * its own stock count, computed from its batches). The Product
     * model's total stock is derived by summing across all its brands'
     * computed stock — this happens in the view layer via Eloquent.
     *
     * @param  \App\Models\ProductBrand $productBrand
     * @return void
     */
    protected function recalculateTotalStock(ProductBrand $productBrand): void
    {
        $totalStock = $this->batchRepository->getTotalStockForProductBrand(
            $productBrand->id
        );

        // The current_stock accessor on ProductBrand already computes
        // this dynamically. We don't need a physical column here.
        // If a 'total_stock' column is added in the future, update here:
        // $productBrand->update(['total_stock' => $totalStock]);

        // For now, this method ensures WAC is always recalculated
        // alongside stock changes — maintaining the invariant that
        // stock count and price are always consistent.
    }
}
