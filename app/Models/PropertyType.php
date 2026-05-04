<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    use HasFactory;
    protected $table = 'property_type';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'status'
    ];

    public function propertyCategory()
    {
        return $this->belongsTo(PropertyCategory::class, 'category_id');
    }

    public function propertySubType()
    {
        return $this->hasMany(PropertyMainType::class, 'ptype_id');
    }
}
