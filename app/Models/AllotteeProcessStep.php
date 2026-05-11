<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteeProcessStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'allottee_id',
        'step_no',
        'title',
        'description',
        'status',
        'completed_at',
        'completed_by',
    ];
}
