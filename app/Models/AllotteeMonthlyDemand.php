<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllotteeMonthlyDemand extends Model
{
    use HasFactory;

    protected $table = 'allottee_monthly_demands';

    protected $fillable = [
        'allottee_id',
        'emi_account_id',
        'order_id',

        'demand_month',
        'demand_year',
        'due_date',

        'opening_principal',

        'annual_interest_rate',
        'penalty_interest_rate',

        'interest_amount',
        'penalty_amount',
        'late_fee',
        'admin_charge',

        'total_demand',

        'paid_amount',
        'balance_amount',

        'demand_status',

        'generated_at',
        'paid_at',

        'remarks',
        'created_by',
    ];

    protected $casts = [
        'due_date'     => 'date',
        'generated_at' => 'datetime',
        'paid_at'      => 'datetime',
    ];

    public function allottee()
    {
        return $this->belongsTo(Allottee::class);
    }

    public function emiAccount()
    {
        return $this->belongsTo(
            AllotteeEmiAccount::class,
            'emi_account_id'
        );
    }

    public function order()
    {
        return $this->belongsTo(
            AllotteePaymentOrder::class,
            'order_id'
        );
    }

    public function refreshDemand()
    {
        if ($this->demand_status === 'paid') {
            return;
        }

        $today = now()->startOfDay();

        $interestRate =
            $today->gt($this->due_date)
            ? ($this->annual_interest_rate + $this->penalty_interest_rate)
            : $this->annual_interest_rate;

        $interestAmount =
            ($this->opening_principal * $interestRate / 100) / 12;

        $lateFee = 0;
        $penaltyAmount = 0;
        $adminCharge = 0;

        if ($today->gt($this->due_date)) {

            $lateFee = round($interestAmount * 0.01, 2);

            $adminCharge = 10;

            $penaltyAmount =
                ($this->opening_principal * $this->penalty_interest_rate / 100) / 12;
        }

        $totalDemand =
            $interestAmount +
            $penaltyAmount +
            $lateFee +
            $adminCharge;

        $this->update([
            'interest_amount' => round($interestAmount, 2),
            'penalty_amount'  => round($penaltyAmount, 2),
            'late_fee'        => round($lateFee, 2),
            'admin_charge'    => round($adminCharge, 2),
            'total_demand'    => round($totalDemand, 2),
            'balance_amount'  => round(
                $totalDemand - $this->paid_amount,
                2
            ),
            'demand_status'   => $today->gt($this->due_date)
                ? 'overdue'
                : $this->demand_status,
        ]);
    }
}
