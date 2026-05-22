<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteeGeneratedDocument extends Model
{
    use HasFactory;
    protected $table = 'allottee_generated_documents';

    protected $fillable = [
        'allottee_id',
        'document_name',
        'document_type',
        'file_name',
        'file_path',
        'generated_by',
        'generated_at',
        'issue_date',
        'document_number',
        'signed_file_name',
        'signed_file_path',
        'signed_uploaded_by',
        'signed_uploaded_at',
    ];
}
