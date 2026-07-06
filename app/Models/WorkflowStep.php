<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    use HasFactory;

    protected $table = 'workflow_steps';

    protected $fillable = [
        'workflow_id',
        'step_order',
        'step_name',
        'step_code',
        'role_id',
        'action_type',
        'can_forward',
        'can_reject',
        'can_send_back',
        'can_upload_document',
        'can_add_note',
        'requires_signature',
        'auto_forward',
        'auto_forward_days',
        'next_step_id',
        'previous_step_id',
        'is_final_step',
        'is_starting_step',
        'notification_template',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    
    public function nextStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'next_step_id');
    }

    public function previousStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'previous_step_id');
    }
}
