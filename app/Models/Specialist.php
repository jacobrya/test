<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salon_id',
        'bio',
        'photo',
        'experience_years',
        'is_active',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true)->where('is_active', true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'specialist_service');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute(): ?float
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : null;
    }
}
