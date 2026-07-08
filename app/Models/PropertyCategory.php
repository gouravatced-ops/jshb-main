<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class PropertyCategory extends Model
{
    use SoftDeletes, EncryptedRouteKey;
    protected $connection = 'adms_jshb';
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

    protected $appends = ['pct_en_id'];

    public function getPctEnIdAttribute()
    {
        return encryptId($this->id);
    }

    public function propertycategoryType()
    {
        return $this->hasMany(PropertyCategory::class, 'category_id');
    }
}
