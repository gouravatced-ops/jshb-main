<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\User;
use App\Models\Role;

class ApplicationAuditTrail extends Model
{
    use HasFactory;

    protected $connection = 'adms_jshb';
    protected $table = 'application_audit_trails';

    // Disable updated_at since it's not in the table
    public const UPDATED_AT = null;

    protected $fillable = [
        'application_id',
        'user_id',
        'role_id',
        'action',
        'module',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
