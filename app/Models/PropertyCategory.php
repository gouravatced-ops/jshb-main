<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class PropertyCategory extends Model
{
    use SoftDeletes, EncryptedRouteKey;
    protected $table = 'property_category';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'status',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function propertycategoryType()
    {
        return $this->hasMany(PropertyCategory::class, 'category_id');
    }
}
