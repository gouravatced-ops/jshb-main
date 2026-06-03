<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteeStepDuration extends Model
{
    use HasFactory;
    protected $table = 'allottee_step_durations';

    protected $fillable = [
        'allottee_id',
        'step_no',
        'started_at',
        'completed_at',
        'duration_min',
        'ip_address',
        'user_agent',
        'created_by'
    ];
}
