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

    protected static function booted()
    {
        static::saving(function ($user) {
            if ($user->role_id) {
                $role = $user->roleRelation ?: Role::find($user->role_id);
                if ($role) {
                    $slug = $role->slug;
                    $user->user_type = match ($slug) {
                        'admin', 'super-admin', 'managing-director' => 'administration',
                        'division-officer',
                        'dealing-assistant',
                        'office-superintendent',
                        'estate-officer',
                        'executive-engineer',
                        'assistant-engineer',
                        'junior-engineer' => 'engineer',
                        'allottee' => 'allottee',
                        'operator' => 'operator',
                        default => 'staff',
                    };
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'role_id',
        'division_id',
        'user_type',
        'login_with_otp',
        'password_created_at',
        'photo',
        'is_locked',
        'status',
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
            'status' => 'boolean',
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

    public function roleRelation()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getRoleAttribute()
    {
        $slug = $this->roleRelation?->slug;

        if (!$slug) {
            return null;
        }

        return match ($slug) {
            'admin', 'super-admin' => 'admin',
            'division-officer'     => 'division',
            'executive-engineer',
            'dealing-assistant',
            'office-superintendent',
            'estate-officer',
            'assistant-engineer',
            'junior-engineer'      => 'engineer',
            'managing-director'    => 'managing',
            'operator'             => 'operator',
            'allottee'             => 'user',
            default                => 'staff',
        };
    }

    public function setRoleAttribute($value)
    {
        $map = [
            'admin'       => 'admin',
            'division'    => 'division-officer',
            'engineer' => 'executive-engineer',
            'managing'    => 'managing-director',
            'operator'    => 'operator',
            'user'        => 'allottee',
            'staff'       => 'staff',
        ];

        if (isset($map[$value])) {
            $role = Role::where('slug', $map[$value])->first();
            if ($role) {
                $this->attributes['role_id'] = $role->id;
            }
        }
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function getRoleDisplayNameAttribute()
    {
        return $this->roleRelation ? $this->roleRelation->name : ucfirst($this->role);
    }

    /**
     * Generate a unique username in format: JSHB{division_code}{5_random_alphanumeric}
     * Used for allottee users.
     */
    public static function generateUniqueUsername(?int $divisionId = null): ?string
    {
        $divisionCode = '';
        if ($divisionId) {
            $divisionCode = Division::where('id', $divisionId)->value('division_code') ?? '';
        }

        do {
            $randomCode = str_pad(mt_rand(10000, 99999), 5, '0', STR_PAD_LEFT);
            $username = 'JSHB' . strtoupper($divisionCode) . $randomCode;
        } while (self::where('username', $username)->exists());

        return $username;
    }

    /**
     * Generate a unique username in format: JSHB{8_random_alphanumeric}
     * Used for member users.
     */
    public static function generateMemberUsername(): string
    {
        do {
            $randomCode = str_pad(mt_rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
            $username = 'JSHB' . $randomCode;
        } while (self::where('username', $username)->exists());

        return $username;
    }
}
