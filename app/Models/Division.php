<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class Division extends Model
{
    use SoftDeletes, EncryptedRouteKey;

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
}