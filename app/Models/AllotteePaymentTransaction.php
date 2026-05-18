<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteePaymentTransaction extends Model
{
    use HasFactory;

    protected $table = 'allottee_payment_transactions';

    protected $fillable = [
        'allottee_id',
        'payment_id',
        'amount',
        'transaction_no',
        'payment_gateway',
        'payment_mode',
        'payment_status',
        'paid_at',
        'receipt_path',
        'gateway_response',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function allottee()
    {
        return $this->belongsTo(Allottee::class);
    }

    public function payment()
    {
        return $this->belongsTo(AllotteeInitialPayment::class, 'payment_id');
    }
}