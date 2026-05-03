<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $table = 'order_item';

    protected $fillable = [
        'order_id',
        'product_brand_id',
        'service_id',
        'quantity',
        'price_per_unit',
        'subtotal_price',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'price_per_unit' => 'decimal:0',
            'subtotal_price' => 'decimal:0',
        ];
    }

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