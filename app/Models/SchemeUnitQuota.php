<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeUnitQuota extends Model
{
    use HasFactory;
    protected $table = 'scheme_unit_quotas';

    public $timestamps = true;

    protected $fillable = [
        'scheme_id',
        'quota_type',
        'total_units',
        'allotted_units',
    ];

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
}
