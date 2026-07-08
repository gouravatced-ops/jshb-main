<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeFinancial extends Model
{
    use HasFactory;
    protected $connection = 'adms_jshb';
    protected $table = 'scheme_financials';
    public $timestamps = true;

    protected $fillable = [
        'scheme_id',
        'property_total_cost',
        'lottery_percentage',
        'lottery_amount',
        'allotment_percentage',
        'allotement_amount',
        'balance_amount',
        'emi_count',
        'normal_interest_rate',
        'emi_without_penalty',
        'penalty_interest_rate',
        'emi_with_penalty',
        'admin_charges',
    ];

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
}
