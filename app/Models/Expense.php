<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $fillable = [
        'total_amount',
        'note',
        'batch_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:0',
            'created_at' => 'datetime',
        ];
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }
}
