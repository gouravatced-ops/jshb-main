<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FutureAllottee;

class FutureAllotteeTransaction extends Model
{
    use HasFactory;

    protected $connection = 'adms_allottees';
    protected $table = 'future_allottee_transactions';

    protected $fillable = [
        'future_allottee_id',
        'transaction_type',
        'payment_stage',
        'amount',
        'payment_mode',
        'payment_status',
        'transaction_no',
        'utr_no',
        'receipt_file',
        'receipt_path',
        'payment_day',
        'payment_month',
        'payment_year',
        'paid_at',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_day' => 'integer',
        'payment_month' => 'integer',
        'payment_year' => 'integer',
    ];

    public function futureAllottee()
    {
        return $this->belongsTo(FutureAllottee::class, 'future_allottee_id');
    }
}
