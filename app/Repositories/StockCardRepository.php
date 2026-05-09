<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * StockCardRepository
 *
 * Pure database query layer for the Stock Card (Kartu Stok) report.
 * Aggregates stock movement data from two distinct sources:
 *
 *   1. Barang Masuk (IN)  → batches table  (stock received from suppliers)
 *   2. Barang Keluar (OUT) → order_items table (stock sold to customers)
 *
 * Both queries are projected into a uniform schema (date, type, description,
 * qty_in, qty_out, product_brand_id) so the Service layer can merge and
 * sort them chronologically to produce a running-balance ledger.
 *
 * All queries use raw SQL via the Query Builder for optimal performance
 * on large datasets. Eloquent lazy-loading is intentionally avoided
 * here to prevent N+1 issues in reporting contexts.
 */
class StockCardRepository
{
    /**
     * Fetch all stock-in movements (batches) for a given ProductBrand
     * within a date range.
     *
     * Each row represents a batch receipt. The batch_code and supplier_name
     * are concatenated into a human-readable description for the ledger.
     *
     * @param  string      $productBrandId  UUID of the product brand
     * @param  string|null $startDate       Start of date range (Y-m-d)
     * @param  string|null $endDate         End of date range (Y-m-d)
     * @return \Illuminate\Support\Collection
     */
    public function getStockInMovements(string $productBrandId, ?string $startDate = null, ?string $endDate = null)
    {
        $query = DB::table('batches')
            ->where('product_brand_id', $productBrandId)
            ->select(
                'created_at as date',
                DB::raw("'in' as type"),
                DB::raw("CONCAT('Batch: ', batch_code, COALESCE(' — ' || supplier_name, '')) as description"),
                'initial_stock as qty_in',
                DB::raw('0 as qty_out'),
                'purchase_price',
                'id as source_id'
            );

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    /**
     * Fetch all stock-out movements (order items from completed/processing orders)
     * for a given ProductBrand within a date range.
     *
     * Joins order_items → orders to filter by status and obtain the
     * order_number for the description column. Only product-based items
     * are included (service items have no stock impact).
     *
     * Status filter: 'processing' and 'completed' orders represent
     * confirmed sales that have consumed (or will consume) inventory.
     *
     * @param  string      $productBrandId  UUID of the product brand
     * @param  string|null $startDate       Start of date range (Y-m-d)
     * @param  string|null $endDate         End of date range (Y-m-d)
     * @return \Illuminate\Support\Collection
     */
    public function getStockOutMovements(string $productBrandId, ?string $startDate = null, ?string $endDate = null)
    {
        $query = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.product_brand_id', $productBrandId)
            ->whereIn('orders.status', ['processing', 'completed'])
            ->select(
                'orders.created_at as date',
                DB::raw("'out' as type"),
                DB::raw("CONCAT('Order: ', orders.order_number) as description"),
                DB::raw('0 as qty_in'),
                'order_items.quantity as qty_out',
                'order_items.price_per_unit as purchase_price',
                'order_items.id as source_id'
            );

        if ($startDate) {
            $query->whereDate('orders.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('orders.created_at', '<=', $endDate);
        }

        return $query->orderBy('orders.created_at', 'asc')->get();
    }

    /**
     * Calculate the opening balance (saldo awal) for a ProductBrand
     * before a given start date.
     *
     * Opening balance = total stock received BEFORE the period
     *                  - total stock sold BEFORE the period
     *
     * This enables the running balance calculation to start from
     * the correct baseline even when filtering by date range.
     *
     * @param  string      $productBrandId  UUID of the product brand
     * @param  string|null $startDate       The start of the reporting period
     * @return int
     */
    public function getOpeningBalance(string $productBrandId, ?string $startDate = null): int
    {
        if (!$startDate) {
            return 0; // No date filter = report starts from zero
        }

        // Total stock received before the period
        $totalIn = (int) DB::table('batches')
            ->where('product_brand_id', $productBrandId)
            ->whereDate('created_at', '<', $startDate)
            ->sum('initial_stock');

        // Total stock sold before the period (only confirmed orders)
        $totalOut = (int) DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.product_brand_id', $productBrandId)
            ->whereIn('orders.status', ['processing', 'completed'])
            ->whereDate('orders.created_at', '<', $startDate)
            ->sum('order_items.quantity');

        return $totalIn - $totalOut;
    }

    /**
     * Get summary statistics for the filtered period.
     *
     * Returns totals for stock in, stock out, and current balance
     * to display in the report header stat cards.
     *
     * @param  string      $productBrandId
     * @param  string|null $startDate
     * @param  string|null $endDate
     * @return array{total_in: int, total_out: int, current_stock: int}
     */
    public function getPeriodSummary(string $productBrandId, ?string $startDate = null, ?string $endDate = null): array
    {
        $inQuery = DB::table('batches')
            ->where('product_brand_id', $productBrandId);
        $outQuery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.product_brand_id', $productBrandId)
            ->whereIn('orders.status', ['processing', 'completed']);

        if ($startDate) {
            $inQuery->whereDate('created_at', '>=', $startDate);
            $outQuery->whereDate('orders.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $inQuery->whereDate('created_at', '<=', $endDate);
            $outQuery->whereDate('orders.created_at', '<=', $endDate);
        }

        $totalIn  = (int) $inQuery->sum('initial_stock');
        $totalOut = (int) $outQuery->sum('order_items.quantity');

        // Current actual stock from active batches (real-time)
        $currentStock = (int) DB::table('batches')
            ->where('product_brand_id', $productBrandId)
            ->where('is_active', true)
            ->sum('current_stock');

        return [
            'total_in'      => $totalIn,
            'total_out'     => $totalOut,
            'current_stock' => $currentStock,
        ];
    }
}
