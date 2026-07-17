<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\User;
use App\Models\Role;
use App\Models\WorkflowStep;

class ApplicationMovement extends Model
{
    use HasFactory;

    protected $connection = 'adms_jshb';
    protected $table = 'application_movements';

    protected $fillable = [
        'application_id',
        'from_user_id',
        'to_user_id',
        'from_role_id',
        'to_role_id',
        'from_step_id',
        'to_step_id',
        'action_type',
        'status',
        'remarks',
        'movement_date',
        'received_date',
        'is_read',
        'read_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'movement_date' => 'datetime',
        'received_date' => 'datetime',
        'read_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function fromRole()
    {
        return $this->belongsTo(Role::class, 'from_role_id');
    }

    public function toRole()
    {
        return $this->belongsTo(Role::class, 'to_role_id');
    }

    public function fromStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'from_step_id');
    }

    public function toStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'to_step_id');
    }
}
