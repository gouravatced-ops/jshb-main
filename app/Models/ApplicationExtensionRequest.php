<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\User;

class ApplicationExtensionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'movement_id',
        'requested_by',
        'request_reason',
        'remarks',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function movement()
    {
        return $this->belongsTo(ApplicationMovement::class, 'movement_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
