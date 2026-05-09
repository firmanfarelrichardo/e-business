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
     * Recalculate selling_price using Weighted Average Cost (WAC) queue model.
     * Only batches with current_stock > 0 are included (depleted batches excluded).
     */
    public function recalculateWAC(): void
    {
        $activeBatches = $this->batches()
            ->where('current_stock', '>', 0)
            ->get(['purchase_price', 'current_stock']);

        if ($activeBatches->isEmpty()) {
            return; // No stock remaining — keep current price
        }

        $totalValue = $activeBatches->sum(fn($b) => $b->purchase_price * $b->current_stock);
        $totalQuantity = $activeBatches->sum('current_stock');

        $wac = intdiv((int) $totalValue, (int) $totalQuantity);

        $this->update(['selling_price' => $wac]);
    }
}
