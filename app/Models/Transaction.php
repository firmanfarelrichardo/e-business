<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'transaction_code',
        'transaction_status',
        'gateway_response',
        'payment_type_id',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'gateway_response' => 'array',
            'created_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_type_id');
    }
}
