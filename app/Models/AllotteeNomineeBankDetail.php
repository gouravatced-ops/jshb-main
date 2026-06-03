<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteeNomineeBankDetail extends Model
{
    use HasFactory;
    protected $table = 'allottee_nominee_bank_details';

    protected $fillable = [
        'allottee_id',
        'nominee_prefix',
        'nominee_name',
        'nominee_relationship',
        'nominee_pan_card',
        'nominee_aadhaar',
        'family_name_prefix',
        'family_name',
        'family_gender',
        'family_dob',
        'family_relationship',
        'family_aadhaar',
        'family_pan',
        'bank_name',
        'bank_account_no',
        'bank_branch',
        'bank_ifsc',
        'bank_account_holder',
        'create_ip_address',
        'update_ip_address',
        'created_by',
        'updated_by'
    ];

    public function allottee()
    {
        return $this->belongsTo(Allottee::class, 'allottee_id');
    }
}
