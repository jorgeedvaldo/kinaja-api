<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'status',
        'status_updated_by',
        'status_updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status_updated_at' => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────────────────

    /**
     * Get the driver profile associated with this user.
     */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    /**
     * Get the restaurants managed by this user.
     */
    public function restaurants(): HasMany
    {
        return $this->hasMany(Restaurant::class);
    }

    /**
     * Get the orders placed by this user (as a client).
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    // ─── Relationships ──────────────────────────────────────────────────

    /**
     * Get the user who last updated the status.
     */
    public function statusUpdater(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    // ─── Helpers ────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isRestaurantOwner(): bool
    {
        return $this->role === 'restaurant_owner';
    }
}
