<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class PropertyMainType extends Model
{
    use HasFactory, SoftDeletes, EncryptedRouteKey;

    protected $table = 'property_sub_type';
    public $timestamps = false;

    protected $fillable = [
        'ptype_id',
        'name',
        'status'
    ];

    protected $appends = ['pctm_en_id'];

    public function getPctmEnIdAttribute()
    {
        return encryptId($this->id);
    }

    // Relationship to PropertyType
    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class, 'ptype_id');
    }

    // Relationship to PropertyCategory through PropertyType
    public function propertyCategory()
    {
        return $this->hasOneThrough(
            PropertyCategory::class,   // final model
            PropertyType::class,       // intermediate model
            'id',                      // Foreign key on PropertyType table (category_id)
            'id',                      // Foreign key on PropertyCategory table
            'ptype_id',                // Local key on PropertyMainType table
            'category_id'              // Local key on PropertyType table
        );
    }
}
