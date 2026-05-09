<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $fillable = [
        'expense_id',
        'product_brand_id',
        'quantity',
        'purchase_price',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:0',
            'subtotal' => 'decimal:0',
            'created_at' => 'datetime',
        ];
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class);
    }
}
