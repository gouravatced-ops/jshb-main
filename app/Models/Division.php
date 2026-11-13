<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;
use App\Models\SubDivision;
use App\Models\Allottee;

class Division extends Model
{
    use SoftDeletes, EncryptedRouteKey;

    protected $connection = 'adms_jshb';

    protected $fillable = [
        'name',
        'division_code',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $appends = ['dv_en_id'];

    public function getDvEnIdAttribute()
    {
        return encryptId($this->id);
    }

    public function subDivisions()
    {
        return $this->hasMany(SubDivision::class, 'division_id');
    }

    public function allottees()
    {
        return $this->hasMany(Allottee::class, 'division_id');
    }
}