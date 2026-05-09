<?php

namespace App\Repositories;

use App\Models\Batch;
use App\Models\ProductBrand;

/**
 * BatchRepository
 *
 * Pure database interaction layer for the Batch model.
 * Contains no business logic — only Eloquent queries.
 *
 * ARCHITECTURE NOTE (Future Stock Report):
 * Having all batch queries centralized here means any future
 * StockReportService can simply inject this repository to access
 * methods like getActiveBatchesWithStock(), getTotalStockForProductBrand(),
 * or a future getStockMovementHistory() — without duplicating queries
 * across multiple services. This is the key benefit of the Repository
 * pattern in a reporting-heavy domain like inventory management.
 */
class BatchRepository
{
    /**
     * Retrieve all batches with optional filtering and eager-loaded relations.
     *
     * Supports filtering by product_brand_id and is_active status.
     * Returns batches ordered by newest-first for dashboard display.
     *
     * @param  array $filters  Optional: product_brand_id, is_active
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll($filters = [])
    {
        $query = Batch::with(['productBrand.product', 'productBrand.brand', 'creator']);

        if (isset($filters['product_brand_id'])) {
            $query->where('product_brand_id', $filters['product_brand_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderByDesc('created_at')->get();
    }

    /**
     * Retrieve paginated batches for the dashboard index view.
     *
     * Pagination prevents memory issues when batch history grows large.
     * Eager-loads the full product hierarchy for display.
     *
     * @param  array $filters  Optional filter criteria
     * @param  int   $perPage  Results per page (default 15)
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated($filters = [], int $perPage = 15)
    {
        $query = Batch::with(['productBrand.product', 'productBrand.brand', 'creator']);

        if (isset($filters['product_brand_id'])) {
            $query->where('product_brand_id', $filters['product_brand_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('batch_code', 'ilike', "%{$search}%")
                  ->orWhereHas('productBrand.product', fn($pq) => $pq->where('name', 'ilike', "%{$search}%"))
                  ->orWhereHas('productBrand.brand', fn($bq) => $bq->where('name', 'ilike', "%{$search}%"));
            });
        }

        return $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();
    }

    /**
     * Find a single batch by UUID with all relations.
     *
     * @param  string $id  UUID of the batch
     * @return \App\Models\Batch
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById($id)
    {
        return Batch::with(['productBrand.product', 'productBrand.brand', 'creator'])->findOrFail($id);
    }

    /**
     * Persist a new batch record.
     *
     * @param  array $data  Mass-assignable batch attributes
     * @return \App\Models\Batch
     */
    public function create(array $data)
    {
        return Batch::create($data);
    }

    /**
     * Update an existing batch record.
     *
     * @param  \App\Models\Batch $batch  The batch model instance
     * @param  array             $data   Attributes to update
     * @return \App\Models\Batch
     */
    public function update(Batch $batch, array $data)
    {
        $batch->update($data);
        return $batch;
    }

    /**
     * Delete a batch record.
     *
     * @param  \App\Models\Batch $batch  The batch to delete
     * @return bool|null
     */
    public function delete(Batch $batch)
    {
        return $batch->delete();
    }

    /**
     * Retrieve active batches with remaining stock, ordered by FIFO.
     *
     * Used by OrderService for stock deduction: oldest batches are
     * consumed first to maintain accurate inventory valuation (WAC).
     *
     * @param  string $productBrandId  UUID of the product brand
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveBatchesWithStock($productBrandId)
    {
        return Batch::where('product_brand_id', $productBrandId)
            ->where('is_active', true)
            ->where('current_stock', '>', 0)
            ->orderBy('created_at', 'asc') // FIFO: oldest batch first
            ->get();
    }

    /**
     * Calculate the total current stock across all active batches
     * for a specific ProductBrand.
     *
     * Uses a raw SUM aggregate for performance instead of loading
     * all batch records into memory.
     *
     * @param  string $productBrandId  UUID of the product brand
     * @return int
     */
    public function getTotalStockForProductBrand(string $productBrandId): int
    {
        return (int) Batch::where('product_brand_id', $productBrandId)
            ->where('is_active', true)
            ->sum('current_stock');
    }

    /**
     * Lock the ProductBrand row for update within a transaction.
     *
     * Pessimistic locking prevents race conditions when two admins
     * add batches to the same product simultaneously: the second
     * transaction will wait until the first completes before reading
     * the stock count.
     *
     * MUST be called inside DB::transaction() for the lock to hold.
     *
     * @param  string $productBrandId  UUID of the product brand
     * @return \App\Models\ProductBrand
     */
    public function lockProductBrandForUpdate(string $productBrandId): ProductBrand
    {
        return ProductBrand::lockForUpdate()->findOrFail($productBrandId);
    }
}
