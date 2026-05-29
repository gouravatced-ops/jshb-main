<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllotteePaymentOrder extends Model
{
    use HasFactory;

    protected $table = 'allottee_payment_orders';

    protected $fillable = [
        'allottee_id',
        'order_type',
        'order_no',
        'title',
        'property_amount',
        'percentage',
        'base_amount',
        'penalty_amount',
        'admin_charge',
        'total_payable',
        'paid_amount',
        'remaining_amount',
        'payment_option',
        'due_date',
        'issued_at',
        'paid_at',
        'order_status',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'property_amount'  => 'decimal:2',
        'percentage'       => 'decimal:2',
        'base_amount'      => 'decimal:2',
        'penalty_amount'   => 'decimal:2',
        'admin_charge'     => 'decimal:2',
        'total_payable'    => 'decimal:2',
        'paid_amount'      => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date'         => 'date',
        'issued_at'        => 'datetime',
        'paid_at'          => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function allottee()
    {
        return $this->belongsTo(Allottee::class);
    }

    public function transactions()
    {
        return $this->hasMany(
            AllotteeTransaction::class,
            'order_id'
        );
    }

    public function emiAccount()
    {
        return $this->hasOne(
            AllotteeEmiAccount::class,
            'order_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getPendingAmountAttribute()
    {
        return max(
            0,
            $this->total_payable - $this->paid_amount
        );
    }

    public function getDelayDaysAttribute()
    {
        if (
            !$this->due_date ||
            now()->lte($this->due_date)
        ) {
            return 0;
        }

        return Carbon::parse(
            $this->due_date
        )->diffInDays(now());
    }

    public function getPenaltyPercentageAttribute()
    {
        $delayDays = $this->delay_days;

        if ($delayDays <= 0) {
            return 0;
        }

        $rule = PaymentPenaltyRule::where('status', 1)
            ->where('from_day', '<=', $delayDays)
            ->where('to_day', '>=', $delayDays)
            ->first();

        return (int) ($rule?->penalty_percentage ?? 0);
    }

    public function getCalculatedPenaltyAmountAttribute()
    {
        return round(
            (
                $this->base_amount *
                $this->penalty_percentage
            ) / 100,
            2
        );
    }

    public function getCalculatedAdminChargeAttribute()
    {
        return $this->delay_days > 0
            ? 10
            : 0;
    }

    public function getCalculatedTotalPayableAttribute()
    {
        return round(
            $this->base_amount +
                $this->calculated_penalty_amount +
                $this->calculated_admin_charge,
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPaid()
    {
        return $this->order_status === 'paid';
    }

    public function isPartial()
    {
        return $this->order_status === 'partial';
    }

    public function isOverdue()
    {
        return $this->order_status === 'overdue';
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER NUMBER
    |--------------------------------------------------------------------------
    */

    public static function generateOrderNo(
        $type = 'ORD'
    ) {
        return strtoupper($type)
            . '-'
            . now()->format('Ymd')
            . '-'
            . rand(100000, 999999);
    }

    /*
    |--------------------------------------------------------------------------
    | PENALTY CALCULATION
    |--------------------------------------------------------------------------
    */

    public function refreshPenalty()
    {
        $delayDays = $this->delay_days;

        $penaltyPercentage = 0;

        if ($delayDays > 0) {

            $rule = PaymentPenaltyRule::where(
                'status',
                1
            )
                ->where('from_day', '<=', $delayDays)
                ->where('to_day', '>=', $delayDays)
                ->first();

            $penaltyPercentage =
                $rule?->penalty_percentage ?? 0;
        }

        $penaltyAmount = round(
            (
                $this->base_amount *
                $penaltyPercentage
            ) / 100,
            2
        );

        $adminCharge =
            $delayDays > 0
            ? 10
            : 0;

        $totalPayable = round(
            $this->base_amount +
                $penaltyAmount +
                $adminCharge,
            2
        );

        $this->update([
            'penalty_amount'   => $penaltyAmount,
            'admin_charge'     => $adminCharge,
            'total_payable'    => $totalPayable,
            'remaining_amount' => max(
                0,
                $totalPayable - $this->paid_amount
            ),
            'order_status'     => (
                $delayDays > 0 &&
                $this->paid_amount <= 0
            )
                ? 'overdue'
                : $this->order_status,
        ]);

        return $this->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    public function refreshPaymentStatus()
    {
        $paid = $this->transactions()
            ->where(
                'payment_status',
                'success'
            )
            ->sum('amount');

        $remaining =
            $this->total_payable - $paid;

        $status = 'issued';

        if ($remaining <= 0) {

            $status = 'paid';
        } elseif ($paid > 0) {

            $status = 'partial';
        } elseif (
            $this->due_date &&
            now()->gt($this->due_date)
        ) {

            $status = 'overdue';
        }

        $this->update([
            'paid_amount'      => $paid,
            'remaining_amount' => max(
                0,
                $remaining
            ),
            'order_status'     => $status,
            'paid_at'          => $remaining <= 0
                ? now()
                : null,
        ]);

        return $this->fresh();
    }
}