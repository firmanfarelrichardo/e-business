<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\StockCardService;
use App\Models\ProductBrand;
use Illuminate\Http\Request;

/**
 * Web\StockCardController
 *
 * Thin controller for the Stock Card (Kartu Stok) report page.
 * Receives filter parameters, delegates data aggregation to the
 * StockCardService, and returns the Blade view with results.
 *
 * The report requires a product_brand_id selection to display
 * meaningful stock movement data for a specific variant.
 */
class StockCardController extends Controller
{
    protected StockCardService $stockCardService;

    public function __construct(StockCardService $stockCardService)
    {
        $this->stockCardService = $stockCardService;
    }

    /**
     * Display the stock card report page.
     *
     * When no product_brand_id is selected, shows only the filter
     * form. When a variant is selected, generates and displays the
     * full stock movement ledger with running balance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Load all product brands for the dropdown filter
        $productBrands = ProductBrand::with(['product', 'brand'])->get();

        // Initialize empty state
        $stockCard       = null;
        $selectedBrand   = null;
        $startDate       = $request->input('start_date');
        $endDate         = $request->input('end_date');
        $productBrandId  = $request->input('product_brand_id');

        // Generate report when a product brand is selected
        if ($productBrandId) {
            $selectedBrand = ProductBrand::with(['product', 'brand'])->find($productBrandId);
            $stockCard     = $this->stockCardService->generateStockCard(
                $productBrandId,
                $startDate,
                $endDate
            );
        }

        return view('dashboard.reports.stock-card', compact(
            'productBrands',
            'stockCard',
            'selectedBrand',
            'startDate',
            'endDate',
            'productBrandId'
        ));
    }
}
