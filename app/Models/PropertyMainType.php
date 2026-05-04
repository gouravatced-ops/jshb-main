<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyMainType extends Model
{
    use HasFactory;

    protected $table = 'property_sub_type';
    public $timestamps = false;

    protected $fillable = [
        'ptype_id',
        'name',
        'status'
    ];

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
