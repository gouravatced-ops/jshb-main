<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class SchemeUnitQuota extends Model
{
    use SoftDeletes, EncryptedRouteKey;
    protected $table = 'scheme_unit_quotas';

    public $timestamps = true;

    protected $fillable = [
        'scheme_id',
        'quota_type_id',
        'total_units',
        'allotted_units',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function quotaType()
    {
        return $this->belongsTo(QuotaType::class, 'quota_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}