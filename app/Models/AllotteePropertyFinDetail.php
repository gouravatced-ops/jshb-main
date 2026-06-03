<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteePropertyFinDetail extends Model
{
    use HasFactory;
    protected $table = 'allottee_property_fin_details';

    protected $fillable = [
        'allottee_id',
        'tentative_price',
        'amount_words',

        'maav_day',
        'maav_month',
        'maav_year',
        'deposit_type',

        'high_income_percent',
        'low_income_percent',

        'deposited_amount',
        'legal_fee',
        'legal_document_fee',

        'total_payment',
        'interim_price',
        'remaining_amount',

        'payment_months',
        'payment_start_month',
        'payment_start_year',

        'last_payment_due_date',
        'interest_calculation_mode',
        'interest_type',
        'pre_interest',
        'late_interest',

        'pre_interest_amount',
        'late_interest_amount',

        'allot_day',
        'allot_month',
        'allot_year',

        'lottery_details',
        'colony_name',
        'plot_number',

        'area_sqft',

        'mohalla',
        'post_office',
        'city',
        'police_station',

        'state',
        'district',

        'north_boundary',
        'south_boundary',
        'east_boundary',
        'west_boundary',

        'ew_north',
        'ew_south',
        'ns_east',
        'ns_west',

        'specified_days',

        'last_day',
        'last_month',
        'last_year',

        'created_ip',
        'updated_ip',
        'created_by',
        'updated_by'
    ];

    public function allottee()
    {
        return $this->belongsTo(Allottee::class, 'allottee_id');
    }
}
