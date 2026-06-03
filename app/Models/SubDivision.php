<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class SubDivision extends Model
{
    use SoftDeletes, EncryptedRouteKey;

    protected $table = 'sub_divisions';

    protected $fillable = [
        'division_id',
        'name',
        'subdivision_code',
        'colony_name',
        'locality_address',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    protected $appends = ['sub_dv_en_id'];

    public function getSubDvEnIdAttribute()
    {
        return encryptId($this->id);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}
