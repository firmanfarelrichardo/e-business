<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $fillable = [
        'batch_code',
        'current_stock',
        'initial_stock',
        'purchase_price',
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

    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}