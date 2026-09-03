<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteeLedger extends Model
{
    use HasFactory;

    protected $table = 'allottee_ledger';

    protected $fillable = [
        'allottee_id',
        'emi_account_id',
        'demand_id',
        'payment_id',
        'order_id',
        'transaction_date',
        'transaction_type',
        'transaction_mode',
        'description',
        'debit_amount',
        'credit_amount',
        'running_principal',
        'running_balance',
        'reference_no',
        'remarks',
        'created_by'
    ];
}
