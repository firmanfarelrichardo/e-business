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
}
