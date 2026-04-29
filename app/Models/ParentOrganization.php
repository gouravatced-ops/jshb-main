<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class ParentOrganization extends Model
{
    use SoftDeletes, EncryptedRouteKey;

    protected $fillable = [
        'name',
        'display_code',
        'address',
        'pin_code',
        'district',
        'locality',
        'police_station',
        'post_office',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'parent_organization_id');
    }
}
