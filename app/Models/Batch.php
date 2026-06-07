<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Batch Model
 *
 * Represents a single stock receipt/batch of a specific product variant.
 * Each batch tracks the initial quantity received, the current remaining
 * stock, the purchase price (cost basis), and the supplier source.
 *
 * Batches are consumed in FIFO order during order fulfillment:
 * the oldest active batch with remaining stock is deducted first.
 *
 * ARCHITECTURE NOTE:
 * This model is intentionally lean — only data structure and relations.
 * All stock calculation logic lives in BatchService, and all queries
 * live in BatchRepository. This separation ensures the model remains
 * testable and reusable across different business contexts.
 */
class Batch extends Model
{
    use HasFactory, HasUuid;

    public $timestamps = false;

    protected $fillable = [
        'batch_code',
        'current_stock',
        'initial_stock',
        'purchase_price',
        'supplier_name',
        'is_active',
        'product_brand_id',
        'created_by',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:0',
            'is_active'      => 'boolean',
            'created_at'     => 'datetime',
        ];
    }

    /**
     * The product variant (brand) this batch belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class);
    }

    /**
     * The user (admin/employee) who created this batch record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculate the percentage of stock remaining.
     *
     * Used by the UI to render stock progress bars.
     *
     * @return float
     */
    public function getStockPercentageAttribute(): float
    {
        if ($this->initial_stock <= 0) return 0;
        return round(($this->current_stock / $this->initial_stock) * 100, 1);
    }
}
