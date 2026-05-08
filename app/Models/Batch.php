<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Batch extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false; // We only have created_at from migration

    protected $fillable = [
        'batch_code',
        'current_stock',
        'initial_stock',
        'purchase_price',
        'is_active',
        'product_brand_id',
        'created_by',
        'created_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime'
    ];

    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
