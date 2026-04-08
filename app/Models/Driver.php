<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'license_plate',
        'current_lat',
        'current_lng',
        'is_online',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'current_lat' => 'decimal:7',
        'current_lng' => 'decimal:7',
        'is_online'   => 'boolean',
    ];

    // ─── Relationships ──────────────────────────────────────────────────

    /**
     * Get the user that owns this driver profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders assigned to this driver.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
