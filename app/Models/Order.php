<?php

namespace App\Models;

<<<<<<< HEAD
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuid;

    public $timestamps = false;
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasFactory, HasUuids;
>>>>>>> 502819c98cd71e85ccf600a6c332d03e00f1c059

    protected $fillable = [
        'order_number',
        'queue_number',
        'status',
        'user_id',
        'employee_id',
        'note',
        'total_price',
        'paid_at',
<<<<<<< HEAD
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
=======
        'completed_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
>>>>>>> 502819c98cd71e85ccf600a6c332d03e00f1c059

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
<<<<<<< HEAD

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
=======
}
>>>>>>> 502819c98cd71e85ccf600a6c332d03e00f1c059
