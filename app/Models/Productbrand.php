<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $fillable = [
        'unit',
        'selling_price',
        'product_id',
        'brand_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'selling_price' => 'decimal:0',
            'created_at'    => 'datetime',
        ];
    }

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

    // Ambil stok total dari semua batch aktif
    public function getTotalStockAttribute(): int
    {
        return $this->batches()->where('is_active', true)->sum('current_stock');
    }
}