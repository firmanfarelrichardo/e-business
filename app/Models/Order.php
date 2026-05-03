<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $fillable = [
        'order_number',
        'queue_number',
        'status',
        'user_id',
        'employee_id',
        'note',
        'total_price',
        'paid_at',
        'created_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'total_price'  => 'decimal:0',
            'paid_at'      => 'datetime',
            'created_at'   => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function items() {
    return $this->hasMany(OrderItem::class);
}
}