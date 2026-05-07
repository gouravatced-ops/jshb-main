<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\State;
use App\Models\District;

class AllotteesContactDetail extends Model
{
    use HasFactory;
    protected $table = 'allottees_contact_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'allottee_id',
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

        'correspondence_address',
        'correspondence_address_hindi',
        'correspondence_state',
        'correspondence_district',
        'correspondence_pincode',
        'correspondence_post_office',
        'correspondence_post_office_hindi',
        'correspondence_police_station',
        'correspondence_police_station_hindi',

        'mobile_number',
        'alternate_mobile',
        'stdCode',
        'landline',
        'whatsapp_number',
        'email',

        'created_by',
        'updated_by',
        'create_ip_address',
        'update_ip_address'
    ];

    public $timestamps = true;

    public function allottee()
    {
        return $this->belongsTo(Allottee::class, 'allottee_id');
    }
}
