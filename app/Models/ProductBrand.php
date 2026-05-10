<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductBrand extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'unit',
        'selling_price',
        'product_id',
        'brand_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCurrentStockAttribute()
    {
        return $this->batches()->where('is_active', true)->sum('current_stock');
    }

    /**
     * Get the dynamically calculated Weighted Average Cost (WAC) based on active batches.
     * Only batches with current_stock > 0 are included (depleted batches excluded).
     * This acts as the "Harga Modal" (Cost Price).
     */
    public function getAverageCostAttribute(): int
    {
        $activeBatches = $this->batches()
            ->where('current_stock', '>', 0)
            ->get(['purchase_price', 'current_stock']);

        if ($activeBatches->isEmpty()) {
            return 0; // No active stock, cost is 0
        }

        $totalValue = $activeBatches->sum(fn($b) => $b->purchase_price * $b->current_stock);
        $totalQuantity = $activeBatches->sum('current_stock');

        return $totalQuantity > 0 ? intdiv((int) $totalValue, (int) $totalQuantity) : 0;
    }
}
