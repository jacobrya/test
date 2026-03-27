<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isSalonOwner(): bool
    {
        return $this->role === 'salon_owner';
    }

    public function isSpecialist(): bool
    {
        return $this->role === 'specialist';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function salon(): HasOne
    {
        return $this->hasOne(Salon::class, 'owner_id');
    }

    public function specialist(): HasOne
    {
        return $this->hasOne(Specialist::class);
    }

    public function clientAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }
}
