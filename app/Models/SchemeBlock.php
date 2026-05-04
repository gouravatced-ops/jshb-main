<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeBlock extends Model
{
    use HasFactory;
    protected $table = 'scheme_blocks';

    protected $fillable = [
        'scheme_id',
        'scheme_property_type',
        'block_name',
        'area_sqft',
        'undivided_land_share',
        'total_buildup',
        'total_area_of_construction',
        'dimension_east',
        'dimension_west',
        'dimension_north',
        'dimension_south',
        'arm_east_west_north',
        'arm_east_west_south',
        'arm_north_south_east',
        'arm_north_south_west',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'dimensions' => 'array',
        'arms' => 'array',
    ];
}