<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\ApplicationMovement;
use App\Models\User;

class ApplicationDocument extends Model
{
    use HasFactory;

    protected $connection = 'adms_jshb';
    protected $table = 'application_documents';

    protected $fillable = [
        'application_id',
        'movement_id',
        'document_type',
        'document_name',
        'file_name',
        'file_path',
        'file_size',
        'file_mime_type',
        'version',
        'is_original',
        'is_verified',
        'verified_by',
        'verified_at',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'uploaded_at' => 'datetime',
        'is_original' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function movement()
    {
        return $this->belongsTo(ApplicationMovement::class, 'movement_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
