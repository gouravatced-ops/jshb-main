<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $connection = 'adms_jshb';
    protected $table = 'districts';

    protected $fillable = [
        'state_id',
        'name_en',
        'name_hi',
    ];

    public function blocks(): HasMany
    {
        return $this->hasMany(BlockList::class, 'district_id');
    }
}
