<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationPdfHistory extends Model
{
    use HasFactory;

    protected $table = 'application_pdf_history';

    // Disable standard timestamps as we use generated_at
    public $timestamps = false;

    protected $fillable = [
        'application_id',
        'document_type',
        'pdf_file_name',
        'pdf_file_path',
        'generated_by',
        'generated_at',
        'pdf_content',
        'is_final',
        'version',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'is_final' => 'boolean',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
