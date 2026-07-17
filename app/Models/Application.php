<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;
use App\Models\Allottee;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Models\User;
use App\Models\Role;
use App\Models\ApplicationMovement;
use App\Models\ApplicationNote;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatusHistory;
use App\Models\ApplicationAuditTrail;

class Application extends Model
{
    use HasFactory, SoftDeletes , EncryptedRouteKey;

    protected $connection = 'adms_jshb';
    protected $table = 'applications';

    protected $fillable = [
        'application_no',
        'application_type',
        'allottee_id',
        'property_id',
        'workflow_id',
        'current_step_id',
        'current_user_id',
        'current_role_id',
        'status',
        'priority',
        'created_date',
        'completed_date',
        'expected_completion_date',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'completed_date' => 'datetime',
        'expected_completion_date' => 'date',
    ];

    public function allottee()
    {
        return $this->belongsTo(Allottee::class, 'allottee_id');
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function currentStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'current_step_id');
    }

    public function currentUser()
    {
        return $this->belongsTo(User::class, 'current_user_id');
    }

    public function currentRole()
    {
        return $this->belongsTo(Role::class, 'current_role_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function movements()
    {
        return $this->hasMany(ApplicationMovement::class, 'application_id');
    }

    public function notes()
    {
        return $this->hasMany(ApplicationNote::class, 'application_id');
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class, 'application_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(ApplicationStatusHistory::class, 'application_id');
    }

    public function auditTrails()
    {
        return $this->hasMany(ApplicationAuditTrail::class, 'application_id');
    }
}
