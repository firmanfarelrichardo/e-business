<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    // GET /dashboard — data utama dashboard
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'today_queue'   => $this->dashboardService->getTodayQueue(),
                'today_income'  => $this->dashboardService->getTodayIncome(),
                'low_stock'     => $this->dashboardService->getLowStockProducts(),
            ],
        ]);
    }

    // GET /dashboard/chart?range=7|30
    public function chart(Request $request): JsonResponse
    {
        $range = $request->input('range', 7);

        return response()->json([
            'success' => true,
            'data'    => $this->dashboardService->getSalesChart((int) $range),
        ]);
    }

    // GET /dashboard/reports/orders — laporan order per customer
    public function reportOrders(Request $request): JsonResponse
    {
        $filters = $request->only(['start_date', 'end_date', 'user_id']);

        return response()->json([
            'success' => true,
            'data'    => $this->dashboardService->getOrderReport($filters),
        ]);
    }

    // GET /dashboard/reports/top-products — produk terlaris
    public function reportTopProducts(Request $request): JsonResponse
    {
        $filters = $request->only(['start_date', 'end_date']);

        return response()->json([
            'success' => true,
            'data'    => $this->dashboardService->getTopProducts($filters),
        ]);
    }

    // GET /dashboard/reports/top-services — jasa terlaris
    public function reportTopServices(Request $request): JsonResponse
    {
        $filters = $request->only(['start_date', 'end_date']);

        return response()->json([
            'success' => true,
            'data'    => $this->dashboardService->getTopServices($filters),
        ]);
    }
}