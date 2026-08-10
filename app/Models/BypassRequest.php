<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BypassRequest extends Model
{
    protected $fillable = [
        'application_id',
        'requested_by_user_id',
        'target_step_id',
        'target_role_id',
        'target_user_id',
        'reason',
        'status',
        'approved_by_user_id',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function targetRole()
    {
        return $this->belongsTo(Role::class, 'target_role_id');
    }
    
    public function targetStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'target_step_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
