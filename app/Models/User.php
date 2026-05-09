<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuid, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'address',
        'role',
        'profile',
        'is_active',
        'last_login_at',
        'created_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function employeeOrders()
    {
        return $this->hasMany(Order::class, 'employee_id');
    }

    // Scope untuk filter berdasarkan role
    public function scopeMembers($query)
    {
        return $query->where('role', 'member');
    }

    public function scopeEmployees($query)
    {
        return $query->where('role', 'employee');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
