<?php

namespace App\Models;

use App\Models\Allottee;
use App\Models\Division;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class SubDivision extends Model
{
    use SoftDeletes, EncryptedRouteKey;

    protected $connection = 'adms_jshb';
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

    public function allottees(): HasMany
    {
        return $this->hasMany(Allottee::class, 'subdivision_id');
    }

    public function schemes(): HasMany
    {
        return $this->hasMany(Scheme::class, 'sub_division_id');
    }
}
