<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class PropertyType extends Model
{
    use HasFactory, SoftDeletes, EncryptedRouteKey;
    protected $connection = 'adms_jshb';
    protected $table = 'property_type';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'status'
    ];

    protected $appends = ['pt_en_id'];

    public function getPtEnIdAttribute()
    {
        return encryptId($this->id);
    }

    public function propertyCategory()
    {
        return $this->belongsTo(PropertyCategory::class, 'category_id');
    }

    public function propertySubType()
    {
        return $this->hasMany(PropertyMainType::class, 'ptype_id');
    }
}
