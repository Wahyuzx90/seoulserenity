<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // Roles: customer | staff | owner
    public function isOwner(): bool  { return $this->role === 'owner'; }
    public function isStaff(): bool  { return $this->role === 'staff'; }
    public function isCustomer(): bool { return $this->role === 'customer'; }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
