<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestHouseRequisition extends Model
{
    protected $fillable = [
        'user_id',
        'engineer_detail_id',
        'district_id',
        'block_id',
        'guest_house_name',
        'purpose',
        'stay_from',
        'stay_to',
        'total_guests',
        'remarks',
        'status',
        'admin_remarks',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'stay_from' => 'date',
            'stay_to' => 'date',
            'approved_at' => 'datetime',
            'total_guests' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function engineer(): BelongsTo
    {
        return $this->belongsTo(EngineerDetail::class, 'engineer_detail_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(BlockList::class, 'block_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
