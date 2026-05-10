<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'cart_id',
        'product_brand_id',
        'service_id',
        'quantity',
        'note',
        'document_path'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
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
