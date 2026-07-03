<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OtpLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'otp_code',
        'verified',
        'purpose',
        'expires_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public $timestamps = true;
    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        return now()->greaterThan($this->expires_at);
    }

    /**
     * Scope: Get latest valid (non-expired, non-verified) OTP
     */
    public function scopeLatestValid($query, $userId, $purpose = 'login')
    {
        return $query->where('user_id', $userId)
            ->where('purpose', $purpose)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->latest();
    }
}
