<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllotteeEmiSchedule extends Model
{
    use HasFactory;

    protected $table = 'allottee_emi_schedules';

    protected $fillable = [
        'emi_account_id',
        'allottee_id',
        'order_id',
        'emi_no',
        'due_date',
        'opening_principal',
        'emi_amount',
        'principal_component',
        'interest_component',
        'penalty_amount',
        'admin_charge',
        'total_payable',
        'paid_amount',
        'balance_amount',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at'  => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(
            AllotteeEmiAccount::class,
            'emi_account_id'
        );
    }
}