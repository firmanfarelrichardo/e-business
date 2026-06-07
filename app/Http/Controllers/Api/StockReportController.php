<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StockReportService;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    protected $stockReportService;

    public function __construct(StockReportService $stockReportService)
    {
        $this->stockReportService = $stockReportService;
    }

    public function lowStockAlert(Request $request)
    {
        $threshold = $request->query('threshold', 10);
        $alerts = $this->stockReportService->getLowStockAlerts($threshold);

        return response()->json([
            'success' => true,
            'message' => 'Low Stock Alerts',
            'data' => $alerts
        ]);
    }

    public function stockRecap(Request $request)
    {
        $recap = $this->stockReportService->getStockRecap($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Stock Recap',
            'data' => $recap
        ]);
    }
}
