<?php

namespace App\Services;

use App\Repositories\StockCardRepository;

/**
 * StockCardService
 *
 * Business logic layer for the Stock Card (Kartu Stok) report.
 * Merges stock-in and stock-out movements from the repository into
 * a single chronological ledger with running balance calculation.
 *
 * This service produces a flat, export-ready data structure that can
 * be consumed by:
 *   - Blade views (HTML table rendering)
 *   - PDF export (via DomPDF or Snappy)
 *   - Excel export (via Laravel Excel / PhpSpreadsheet)
 *   - JSON API response
 *
 * The data format is intentionally kept as a simple array of associative
 * arrays (not Eloquent models) for maximum portability across output formats.
 */
class StockCardService
{
    protected StockCardRepository $stockCardRepository;

    public function __construct(StockCardRepository $stockCardRepository)
    {
        $this->stockCardRepository = $stockCardRepository;
    }

    /**
     * Generate the complete stock card ledger for a ProductBrand.
     *
     * Merges IN and OUT movements, sorts chronologically, then
     * calculates a running balance starting from the opening balance.
     *
     * Returns an associative array with:
     *   - opening_balance: int (saldo awal before the period)
     *   - movements: array of movement rows with running balance
     *   - closing_balance: int (saldo akhir at end of period)
     *   - summary: period totals (total_in, total_out, current_stock)
     *
     * @param  string      $productBrandId  UUID of the product brand
     * @param  string|null $startDate       Start date filter (Y-m-d)
     * @param  string|null $endDate         End date filter (Y-m-d)
     * @return array
     */
    public function generateStockCard(string $productBrandId, ?string $startDate = null, ?string $endDate = null): array
    {
        // Step 1: Get opening balance (all movements before start date)
        $openingBalance = $this->stockCardRepository->getOpeningBalance(
            $productBrandId,
            $startDate
        );

        // Step 2: Fetch IN and OUT movements within the period
        $stockIn = $this->stockCardRepository->getStockInMovements(
            $productBrandId,
            $startDate,
            $endDate
        );

        $stockOut = $this->stockCardRepository->getStockOutMovements(
            $productBrandId,
            $startDate,
            $endDate
        );

        // Step 3: Merge and sort chronologically
        $merged = $stockIn->concat($stockOut)->sortBy('date')->values();

        // Step 4: Calculate running balance for each row
        $runningBalance = $openingBalance;
        $movements = [];

        foreach ($merged as $row) {
            $qtyIn  = (int) $row->qty_in;
            $qtyOut = (int) $row->qty_out;

            $runningBalance += $qtyIn;
            $runningBalance -= $qtyOut;

            $movements[] = [
                'date'            => $row->date,
                'type'            => $row->type,
                'type_label'      => $row->type === 'in' ? 'Masuk' : 'Keluar',
                'description'     => $row->description,
                'qty_in'          => $qtyIn,
                'qty_out'         => $qtyOut,
                'purchase_price'  => (float) $row->purchase_price,
                'running_balance' => $runningBalance,
                'source_id'       => $row->source_id,
            ];
        }

        // Step 5: Get period summary statistics
        $summary = $this->stockCardRepository->getPeriodSummary(
            $productBrandId,
            $startDate,
            $endDate
        );

        return [
            'opening_balance' => $openingBalance,
            'movements'       => $movements,
            'closing_balance' => $runningBalance,
            'summary'         => $summary,
        ];
    }

    /**
     * Format the stock card data for export (PDF/Excel).
     *
     * Produces a flat array of rows with human-readable headers,
     * suitable for direct rendering into a table-based export format.
     * Includes an opening balance row and a closing balance footer.
     *
     * @param  array $stockCard  Output from generateStockCard()
     * @return array{headers: array, rows: array}
     */
    public function formatForExport(array $stockCard): array
    {
        $headers = [
            'Tanggal',
            'Tipe Mutasi',
            'Keterangan',
            'Qty Masuk',
            'Qty Keluar',
            'Saldo',
        ];

        $rows = [];

        // Opening balance row
        $rows[] = [
            'date'        => '-',
            'type'        => '-',
            'description' => 'Saldo Awal',
            'qty_in'      => '-',
            'qty_out'     => '-',
            'balance'     => $stockCard['opening_balance'],
        ];

        // Movement rows
        foreach ($stockCard['movements'] as $m) {
            $rows[] = [
                'date'        => \Carbon\Carbon::parse($m['date'])->format('d/m/Y H:i'),
                'type'        => $m['type_label'],
                'description' => $m['description'],
                'qty_in'      => $m['qty_in'] > 0 ? $m['qty_in'] : '-',
                'qty_out'     => $m['qty_out'] > 0 ? $m['qty_out'] : '-',
                'balance'     => $m['running_balance'],
            ];
        }

        // Closing balance row
        $rows[] = [
            'date'        => '-',
            'type'        => '-',
            'description' => 'Saldo Akhir',
            'qty_in'      => '-',
            'qty_out'     => '-',
            'balance'     => $stockCard['closing_balance'],
        ];

        return [
            'headers' => $headers,
            'rows'    => $rows,
        ];
    }
}
