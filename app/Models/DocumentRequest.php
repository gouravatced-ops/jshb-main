<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    use HasFactory;

    protected $table = 'document_requests';

    protected $fillable = [
        'application_id',
        'allottee_id',
        'document_master_id',
        'requested_by',
        'remarks',
        'status',
        'expires_at',
        'uploaded_document_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function allottee()
    {
        return $this->belongsTo(Allottee::class, 'allottee_id');
    }

    public function documentMaster()
    {
        // DocumentMaster is in adms_allottees database, so we might need a custom relation if connection differs.
        // But Laravel usually handles it if the model has `protected $connection`.
        return $this->belongsTo(DocumentMaster::class, 'document_master_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function uploadedDocument()
    {
        return $this->belongsTo(AllotteeDocument::class, 'uploaded_document_id');
    }
}
