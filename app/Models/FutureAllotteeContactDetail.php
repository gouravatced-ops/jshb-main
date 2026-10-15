<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FutureAllottee;

class FutureAllotteeContactDetail extends Model
{
    use HasFactory;

    protected $connection = 'adms_allottees';
    protected $table = 'future_allottee_contact_details';

    protected $fillable = [
        'future_allottee_id',
        'relation_type',
        'prefix_relation_eng',
        'relation_name',
        'prefix_relation_hindi',
        'relation_name_hindi',
        'relation_address',
        'relation_address_hindi',
        'relation_state',
        'relation_district',
        'relation_pincode',
        'relation_post_office',
        'relation_post_office_hindi',
        'relation_police_station',
        'relation_police_station_hindi',
        'same_as_relation_copy',
        'present_address',
        'present_address_hindi',
        'present_state',
        'present_district',
        'present_pincode',
        'present_post_office',
        'present_post_office_hindi',
        'present_police_station',
        'present_police_station_hindi',
        'same_as_present_place_residance',
        'permanent_address',
        'permanent_address_hindi',
        'permanent_state',
        'permanent_district',
        'permanent_pincode',
        'permanent_post_office',
        'permanent_post_office_hindi',
        'permanent_police_station',
        'permanent_police_station_hindi',
        'same_as_permanent_address',
    ];

    protected $casts = [
        'same_as_relation_copy' => 'boolean',
        'same_as_present_place_residance' => 'boolean',
        'same_as_permanent_address' => 'boolean',
    ];

    public function futureAllottee()
    {
        return $this->belongsTo(FutureAllottee::class, 'future_allottee_id');
    }
}
