<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DashboardService
{
    // Antrian hari ini (order pending & process)
    public function getTodayQueue(): array
    {
        $orders = DB::table('orders')
            ->whereDate('created_at', today())
            ->whereIn('status', ['pending', 'process'])
            ->select('id', 'queue_number', 'status', 'total_price', 'created_at')
            ->orderBy('queue_number')
            ->get();

        return [
            'total'  => $orders->count(),
            'orders' => $orders,
        ];
    }

    // Pendapatan hari ini dari transaksi success
    public function getTodayIncome(): array
    {
        $total = DB::table('transactions')
            ->whereDate('created_at', today())
            ->where('transaction_status', 'success')
            ->sum('gross_amount');

        return [
            'total'    => (float) $total,
            'currency' => 'IDR',
        ];
    }

    // Produk dengan stok menipis (< 5)
    public function getLowStockProducts(): array
    {
        $products = DB::table('product_brands')
            ->join('products', 'product_brands.product_id', '=', 'products.id')
            ->join('brands', 'product_brands.brand_id', '=', 'brands.id')
            ->where('product_brands.current_stock', '<', 5)
            ->select(
                'product_brands.id',
                'products.name as product_name',
                'brands.name as brand_name',
                'product_brands.current_stock',
                'product_brands.unit'
            )
            ->get()
            ->toArray();

        return $products;
    }

    // Grafik penjualan 7 atau 30 hari terakhir
    public function getSalesChart(int $days = 7): array
    {
        $startDate = now()->subDays($days - 1)->startOfDay();

        $income = DB::table('transactions')
            ->where('created_at', '>=', $startDate)
            ->where('transaction_status', 'success')
            ->selectRaw('DATE(created_at) as date, SUM(gross_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $expense = DB::table('expenses')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Buat array lengkap per hari
        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date           = now()->subDays($i)->format('Y-m-d');
            $result[] = [
                'date'    => $date,
                'income'  => (float) ($income[$date]->total ?? 0),
                'expense' => (float) ($expense[$date]->total ?? 0),
                'profit'  => (float) ($income[$date]->total ?? 0) - (float) ($expense[$date]->total ?? 0),
            ];
        }

        return $result;
    }

    // Laporan order per customer
    public function getOrderReport(array $filters = [])
    {
        $query = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name as customer_name',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_price) as total_spent')
            )
            ->groupBy('users.id', 'users.name');

        if (!empty($filters['start_date'])) {
            $query->whereDate('orders.created_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->whereDate('orders.created_at', '<=', $filters['end_date']);
        }
        if (!empty($filters['user_id'])) {
            $query->where('users.id', $filters['user_id']);
        }

        return $query->orderByDesc('total_orders')->paginate(10);
    }

    // Produk terlaris
    public function getTopProducts(array $filters = [])
    {
        $query = DB::table('order_items')
            ->join('product_brands', 'order_items.product_brand_id', '=', 'product_brands.id')
            ->join('products', 'product_brands.product_id', '=', 'products.id')
            ->join('brands', 'product_brands.brand_id', '=', 'brands.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotNull('order_items.product_brand_id')
            ->where('orders.status', 'finish')
            ->select(
                'products.name as product_name',
                'brands.name as brand_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal_price) as total_revenue')
            )
            ->groupBy('products.name', 'brands.name');

        if (!empty($filters['start_date'])) {
            $query->whereDate('orders.created_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->whereDate('orders.created_at', '<=', $filters['end_date']);
        }

        return $query->orderByDesc('total_sold')->limit(10)->get();
    }

    // Jasa terlaris
    public function getTopServices(array $filters = [])
    {
        $query = DB::table('order_items')
            ->join('services', 'order_items.service_id', '=', 'services.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotNull('order_items.service_id')
            ->where('orders.status', 'finish')
            ->select(
                'services.name as service_name',
                DB::raw('SUM(order_items.quantity) as total_ordered'),
                DB::raw('SUM(order_items.subtotal_price) as total_revenue')
            )
            ->groupBy('services.name');

        if (!empty($filters['start_date'])) {
            $query->whereDate('orders.created_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->whereDate('orders.created_at', '<=', $filters['end_date']);
        }

        return $query->orderByDesc('total_ordered')->limit(10)->get();
    }
}