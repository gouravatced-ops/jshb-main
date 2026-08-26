<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'notice_type',
        'target_user_type',
        'target_division_id',
        'target_user_id',
        'start_date',
        'end_date',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'target_division_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        });
    }
}
