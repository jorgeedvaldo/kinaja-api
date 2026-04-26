<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'owns_motorcycle',
        'id_document_path', 'driver_license_path',
        'status', 'rejection_reason',
        'reviewed_by', 'reviewed_at', 'user_id'
    ];

    protected $casts = [
        'owns_motorcycle' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
