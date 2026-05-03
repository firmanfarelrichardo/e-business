<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuid, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'address',
        'role',
        'profile',
        'is_active',
        'created_by',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // Relasi ke user yang membuat akun ini
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
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