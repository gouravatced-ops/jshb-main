<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllotteeEmiAccount extends Model
{
    use HasFactory;

    protected $table = 'allottee_emi_accounts';

    protected $fillable = [
        'allottee_id',
        'order_id',
        'account_no',
        'principal_amount',
        'annual_interest_rate',
        'penalty_interest_rate',
        'admin_charge',
        'tenure_months',
        'emi_amount',
        'total_interest',
        'total_payable',
        'paid_amount',
        'remaining_amount',
        'emi_start_date',
        'emi_end_date',
        'account_status',
        'closed_at',
        'created_by',
    ];

    protected $casts = [
        'emi_start_date' => 'date',
        'emi_end_date'   => 'date',
        'closed_at'      => 'datetime',
    ];

    public function allottee()
    {
        return $this->belongsTo(Allottee::class);
    }

    public function order()
    {
        return $this->belongsTo(AllotteePaymentOrder::class, 'order_id');
    }

    public function schedules()
    {
        return $this->hasMany(
            AllotteeEmiSchedule::class,
            'emi_account_id'
        );
    }

    public function demands()
    {
        return $this->hasMany(
            AllotteeMonthlyDemand::class,
            'emi_account_id'
        );
    }
}
