<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteeEmiLedger extends Model
{
    use HasFactory;
    protected $table = 'allottee_emi_ledger';

    protected $fillable = [

        'allottee_id',
        'calculation_type',
        'total_amount',
        'total_emi_count',

        'start_month',
        'start_year',

        'last_emi_month',
        'last_emi_year',

        'amount_without_penalty',
        'amount_with_penalty',

        'without_penalty_count',
        'with_penalty_count',

        'completed_emi',
        'late_emi',
        'remaining_emi',

        'total_paid',
        'total_remaining',
        'current_balance',

        'emi_status',
        'expected_emi',
        'payment_gap',

        'emi_active',

        'emi_config',
        'emi_inputs',
        'emi_timeline',
        'emi_calculated'
    ];

    protected $casts = [
        'emi_config' => 'array',
        'emi_inputs' => 'array',
        'emi_timeline' => 'array',
        'emi_calculated' => 'array'
    ];
}
