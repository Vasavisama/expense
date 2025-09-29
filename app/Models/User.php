<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'is_active',
];

protected $casts = [
    'email_verified_at' => 'datetime',
    'activated_at'     => 'datetime',
    'is_active'        => 'boolean',
];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Rest of your JWT implementation
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role' => $this->role];  // Add role to JWT payload for easy access
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
