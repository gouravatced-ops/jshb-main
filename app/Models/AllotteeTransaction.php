<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllotteeTransaction extends Model
{
    use HasFactory;
    protected $connection = 'adms_allottees';
    protected $table = 'allottee_transactions';

    protected $fillable = [
        'allottee_id',
        'order_id',
        'demand_id',
        'transaction_type',
        'payment_stage',
        'amount',
        'principal_amount',
        'interest_amount',
        'penalty_amount',
        'admin_charge',
        'total_amount',
        'payment_mode',
        'payment_status',
        'transaction_no',
        'utr_no',
        'receipt_file',
        'receipt_path',
        'payment_day',
        'payment_month',
        'payment_year',
        'remarks',
        'paid_at',
        'created_by',
    ];

    protected $casts = [
        'amount'             => 'decimal:2',
        'principal_amount'   => 'decimal:2',
        'interest_amount'    => 'decimal:2',
        'penalty_amount'     => 'decimal:2',
        'admin_charge'       => 'decimal:2',
        'total_amount'       => 'decimal:2',
        'paid_at'            => 'datetime',
    ];

    // Relations
    public function allottee()
    {
        return $this->belongsTo(
            Allottee::class,
            'allottee_id'
        );
    }

    // Helpers
    public function isSuccess()
    {
        return $this->payment_status === 'success';
    }
    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }
    public function isRefunded()
    {
        return $this->payment_status === 'refunded';
    }
    public function isEmiPayment()
    {
        return $this->transaction_type === 'emi_payment';
    }
    public function isExtraPayment()
    {
        return $this->transaction_type === 'extra_payment';
    }

    // Scopes
    public function scopeSuccess($query)
    {
        return $query->where(
            'payment_status',
            'success'
        );
    }
    public function scopeEmi($query)
    {
        return $query->where(
            'transaction_type',
            'emi_payment'
        );
    }
    public function scopeExtra($query)
    {
        return $query->where(
            'transaction_type',
            'extra_payment'
        );
    }

    // AUTO GENERATE TRANSACTION NO
    protected static function booted()
    {
        static::creating(function ($transaction) {
            if (!$transaction->transaction_no) {
                $transaction->transaction_no =
                    'TXN-' .
                    strtoupper(uniqid());
            }
        });
    }
}