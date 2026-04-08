<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'cuisine_type',
        'cover_image',
        'rating',
        'prep_time_mins',
        'is_open',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating'         => 'decimal:1',
        'prep_time_mins' => 'integer',
        'is_open'        => 'boolean',
    ];

    // ─── Relationships ──────────────────────────────────────────────────

    /**
     * Get the owner/manager of this restaurant.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias: Get the owner of this restaurant.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the products belonging to this restaurant.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the orders placed at this restaurant.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
