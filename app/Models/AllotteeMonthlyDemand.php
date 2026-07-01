<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteeMonthlyDemand extends Model
{
    use HasFactory;
    protected $connection = 'adms_allottees';

    protected $table = 'allottee_emi_demands';

    const STATUS_PENDING  = 'pending';
    const STATUS_PARTIAL  = 'partial';
    const STATUS_PAID     = 'paid';
    const STATUS_OVERDUE  = 'overdue';

    protected $fillable = [
        'allottee_id',
        'emi_account_id',
        'order_id',

        'emi_no',
        'due_date',

        'opening_balance',
        'emi_amount',

        'interest_rate',
        'interest_amount',

        'penalty_interest_rate',
        'penalty_interest_amount',

        'principle_amount',
        'late_fine_penalty',
        'penalty_admin_charges',

        'annualized_amount',
        'balance_amount',

        'total_demand_amount',
        'total_paid_amount',

        'demand_status',
        'outstanding_amount',

        'is_late_payment',

        'generated_at',
        'payment_date',
        'paid_at',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'due_date'     => 'date',
        'generated_at' => 'datetime',
        'paid_at'      => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPaid(): bool
    {
        return $this->demand_status === self::STATUS_PAID;
    }

    public function isOverdue(): bool
    {
        return now()->startOfDay()->gt($this->due_date);
    }

    protected function calculateInterestAmount(): float
    {
        return round(
            ($this->opening_balance * $this->interest_rate / 100) / 12,
            2
        );
    }

    protected function calculatePenaltyAmount(): float
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return round(
            ($this->opening_balance * $this->penalty_interest_rate / 100) / 12,
            2
        );
    }

    protected function calculateLateFee(float $interestAmount): float
    {
        return $this->isOverdue()
            ? round($interestAmount * 0.01, 2)
            : 0;
    }

    protected function calculateAdminCharge(): float
    {
        return $this->isOverdue() ? 10 : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Demand Refresh
    |--------------------------------------------------------------------------
    */

    public function refreshDemand(): self
    {
        if ($this->isPaid()) {
            return $this;
        }

        $interestAmount = $this->calculateInterestAmount();

        $penaltyAmount = $this->calculatePenaltyAmount();

        $lateFee = $this->calculateLateFee($interestAmount);

        $adminCharge = $this->calculateAdminCharge();

        $totalDemand = round(
            $interestAmount +
                $penaltyAmount +
                $lateFee +
                $adminCharge,
            2
        );

        $balanceAmount = round(
            max(0, $totalDemand - $this->paid_amount),
            2
        );

        $status = $this->isOverdue()
            ? self::STATUS_OVERDUE
            : $this->demand_status;

        $this->fill([
            'interest_amount' => $interestAmount,
            'penalty_amount'  => $penaltyAmount,
            'late_fee'        => $lateFee,
            'admin_charge'    => $adminCharge,
            'total_demand'    => $totalDemand,
            'balance_amount'  => $balanceAmount,
            'demand_status'   => $status,
        ])->save();

        return $this;
    }
}