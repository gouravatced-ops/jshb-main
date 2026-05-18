<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteeProcessStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'allottee_id',

        'menu_key',
        'sub_menu_key',

        'step_no',

        'title',
        'description',
        'blade',

        'status',
        'is_active',

        'started_at',
        'completed_at',
        'due_date',

        'remarks',
        'meta',

        'completed_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'meta'         => 'array',
        'is_active'    => 'boolean',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
        'due_date'     => 'datetime',
    ];

    // Relationships

    public function allottee()
    {
        return $this->belongsTo(Allottee::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}