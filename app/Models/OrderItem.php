<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderItem extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false; // Based on migration

    protected $fillable = [
        'subtotal_price',
        'quantity',
        'price_per_unit',
        'note',
        'order_id',
        'product_brand_id',
        'service_id',
        'cogs',
        'document_path'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
