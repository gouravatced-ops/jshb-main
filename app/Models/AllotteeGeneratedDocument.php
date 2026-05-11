<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteeGeneratedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'allottee_id',
        'document_type',
        'file_name',
        'file_path',
        'generated_by',
        'generated_at',
    ];
}
