<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class Scheme extends Model
{
    use SoftDeletes, EncryptedRouteKey;
    protected $table = 'schemes';

    public $timestamps = true;

    protected $fillable = [
        'division_id',
        'sub_division_id',
        'pcategory_id',
        'p_type_id',
        'p_sub_type_id',
        'quarter_type_id',
        'scheme_name',
        'scheme_name_hindi',
        'scheme_code',
        'total_units',
        'lease_period',
        'initiation_year',
        'scheme_start_date',
        'scheme_end_date',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'scheme_start_date' => 'datetime',
        'scheme_end_date' => 'datetime',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function subDivision()
    {
        return $this->belongsTo(SubDivision::class);
    }

    public function propertyCategory()
    {
        return $this->belongsTo(PropertyCategory::class, 'pcategory_id');
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class, 'p_type_id');
    }

    public function propertySubType()
    {
        return $this->belongsTo(PropertyMainType::class, 'p_sub_type_id');
    }

    public function quarterType()
    {
        return $this->belongsTo(QuarterType::class, 'quarter_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function blocks()
    {
        return $this->hasMany(SchemeBlock::class, 'scheme_id')
            ->where('status', 1);
    }

    public function quotas()
    {
        return $this->hasMany(SchemeUnitQuota::class);
    }

    public function financial()
    {
        return $this->hasOne(SchemeFinancial::class);
    }

    public function quarterFees()
    {
        return $this->hasMany(SchemeQuarterFee::class, 'scheme_id');
    }

    public function schemeFinance()
    {
        return $this->hasOne(
            SchemeFinancial::class,
            'scheme_id'
        );
    }
}
