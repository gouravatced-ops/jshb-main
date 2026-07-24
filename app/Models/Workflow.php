<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use HasFactory;

    protected $connection = 'adms_jshb';
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

    public function requiredDocuments()
    {
        return $this->belongsToMany(DocumentMaster::class, 'workflow_document_master', 'workflow_id', 'document_master_id')
                    ->withPivot('is_required')
                    ->withTimestamps();
    }
}
