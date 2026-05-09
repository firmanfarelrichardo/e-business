<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBatchRequest;
use App\Services\BatchService;
use App\Models\ProductBrand;

/**
 * Web\BatchController
 *
 * Thin controller for the dashboard batch stock management page.
 * Delegates all business logic (stock recalculation, locking) to BatchService.
 * Handles only request routing and view rendering.
 *
 * ARCHITECTURE NOTE:
 * This controller coexists with Api\BatchController. The API controller
 * returns JSON for external integrations, while this controller returns
 * Blade views for the admin dashboard. Both share the same BatchService,
 * ensuring consistent business rules regardless of the entry point.
 */
class BatchController extends Controller
{
    protected BatchService $batchService;

    public function __construct(BatchService $batchService)
    {
        $this->batchService = $batchService;
    }

    /**
     * Display the batch stock management dashboard page.
     *
     * Fetches paginated batches with search/filter support and
     * the product brand list for the add-batch modal dropdown.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $filters = [];

        if (request()->filled('product_brand_id')) {
            $filters['product_brand_id'] = request('product_brand_id');
        }

        if (request()->filled('search')) {
            $filters['search'] = request('search');
        }

        $batches = $this->batchService->getPaginatedBatches($filters, 15);

        // Load product brands for the creation modal dropdown
        $productBrands = ProductBrand::with(['product', 'brand'])->get();

        // Calculate stock summary stats for the header cards
        $totalBatches   = \App\Models\Batch::count();
        $activeBatches  = \App\Models\Batch::where('is_active', true)->count();
        $totalStockUnits = \App\Models\Batch::where('is_active', true)->sum('current_stock');

        return view('dashboard.batches.index', compact(
            'batches',
            'productBrands',
            'totalBatches',
            'activeBatches',
            'totalStockUnits'
        ));
    }

    /**
     * Store a newly created batch.
     *
     * The StoreBatchRequest validates and authorizes. The service
     * handles locking, stock recalculation, and WAC repricing.
     *
     * @param  \App\Http\Requests\StoreBatchRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBatchRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->id();

            $this->batchService->createBatch($data);

            return redirect()->route('dashboard.batches')
                ->with('success', 'Batch stok berhasil ditambahkan. Stok & harga WAC telah diperbarui otomatis.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan batch: ' . $e->getMessage());
        }
    }

    /**
     * Toggle a batch's active/inactive status.
     *
     * Deactivating a batch removes it from stock counts and WAC
     * without deleting the historical record.
     *
     * @param  string $id  UUID of the batch
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleActive(string $id)
    {
        try {
            $this->batchService->toggleBatchActive($id);

            return redirect()->route('dashboard.batches')
                ->with('success', 'Status batch berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Delete a batch record.
     *
     * @param  string $id  UUID of the batch
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        try {
            $this->batchService->deleteBatch($id);

            return redirect()->route('dashboard.batches')
                ->with('success', 'Batch berhasil dihapus. Stok & WAC telah diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus batch: ' . $e->getMessage());
        }
    }
}
