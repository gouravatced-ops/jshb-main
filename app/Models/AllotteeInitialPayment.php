<?php

// app/Models/AllotteeInitialPayment.php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AllotteeInitialPayment extends Model
{
    protected $fillable = [
        'allottee_id',
        'scheme_id',
        'property_amount',
        'initial_percentage',
        'initial_amount',
        'penalty_percentage',
        'penalty_amount',
        'total_payable_amount',
        'issue_date',
        'paid_date',
        'due_date',
        'payment_mode',
        'payment_status',
        'paid_amount',
        'transaction_no',
        'payment_gateway',
        'payment_received_by',
        'remarks',
        'generated_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date'   => 'date',
        'paid_date' => 'datetime',
    ];

    public function refreshPenalty()
    {
        if ($this->payment_status === 'paid') {
            return $this;
        }

        $today = now();

        $monthsLate = 0;

        if ($today->gt($this->due_date)) {

            $daysLate = $today->diffInDays($this->due_date);

            $monthsLate = ceil($daysLate / 30);
        }

        // 1st Month = 1.5%
        // Next Month = +2.5%

        $penaltyPercentage = 0;

        if ($monthsLate >= 1) {
            $penaltyPercentage += 1.5;
        }

        if ($monthsLate > 1) {
            $penaltyPercentage += ($monthsLate - 1) * 2.5;
        }

        $penaltyAmount = (
            $this->property_amount * $penaltyPercentage
        ) / 100;

        $this->penalty_percentage = $penaltyPercentage;

        $this->penalty_amount = $penaltyAmount;

        $this->total_payable_amount =
            $this->initial_amount + $penaltyAmount;

        $this->save();

        return $this;
    }

    public function allottee()
    {
        return $this->belongsTo(Allottee::class);
    }
}
