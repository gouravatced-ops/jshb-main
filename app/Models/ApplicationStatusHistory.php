<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\User;

class ApplicationStatusHistory extends Model
{
    use HasFactory;

    protected $connection = 'adms_jshb';
    protected $table = 'application_status_history';

    // Disable standard timestamps since we only have changed_at
    public $timestamps = false;

    protected $fillable = [
        'application_id',
        'status_from',
        'status_to',
        'changed_by',
        'remarks',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
