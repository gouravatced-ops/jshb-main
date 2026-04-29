<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\LoginLog;
use App\Models\EngineerDetail;
use App\Models\GuestHouseRequisition;
use App\Models\OtpLog;
use App\Models\UserDetail;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'login_with_otp',
        'password_created_at',
        'photo',
        'is_locked',
        'secure_pin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'secure_pin',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password_created_at' => 'datetime',
            'login_with_otp' => 'boolean',
            'is_locked' => 'boolean',
            'password' => 'hashed',
            'secure_pin' => 'hashed',
        ];
    }

    /**
     * Get the user detail associated with the user.
     */
    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }
    
    // If you want to keep both relationship names
    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    public function otpLogs()
    {
        return $this->hasMany(OtpLog::class);
    }

    public function engineerDetail()
    {
        return $this->hasOne(EngineerDetail::class);
    }

    public function requisitions()
    {
        return $this->hasMany(GuestHouseRequisition::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
