<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentMaster extends Model
{
    use HasFactory;
    protected $connection = 'adms_allottees';
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
        $database = config('database.connections.adms_jshb.database', '30062026_adms_jshb');
        return $this->belongsToMany(Workflow::class, "{$database}.workflow_document_master", 'document_master_id', 'workflow_id')
                    ->withPivot('is_required')
                    ->withTimestamps();
    }
}
