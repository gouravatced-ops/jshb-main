<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentMaster extends Model
{
    use HasFactory;
    protected $connection = 'adms_jshb';
    protected $table = 'document_master';

    protected $fillable = [
        'document_name',
        'document_key',
        'document_category',
        'sort_order',
        'status'
    ];

    public function workflows()
    {
        return $this->belongsToMany(Workflow::class, 'workflow_document_master', 'document_master_id', 'workflow_id')
            ->withPivot('is_required')
            ->withTimestamps();
    }
}
