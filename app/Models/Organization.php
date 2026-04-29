<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class Organization extends Model
{
    use SoftDeletes, EncryptedRouteKey;

    protected $fillable = [
        'name',
        'address',
        'display_code',
        'pin_code',
        'state',
        'district',
        'locality',
        'police_station',
        'post_office',
        'status',
        'parent_organization_id',
        'district_wise_posting',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'district_wise_posting' => 'boolean',
        ];
    }

    public function parentOrganization()
    {
        return $this->belongsTo(ParentOrganization::class, 'parent_organization_id');
    }
}
