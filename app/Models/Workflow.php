<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use HasFactory;

    protected $table = 'workflows';

    protected $fillable = [
        'name',
        'slug',
        'application_type',
        'description',
        'is_active',
    ];

    public function steps()
    {
        return $this->hasMany(WorkflowStep::class, 'workflow_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'workflow_id');
    }
}
