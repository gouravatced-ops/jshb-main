<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class Department extends Model
{
    use SoftDeletes, EncryptedRouteKey;

    protected $fillable = [
        'name',
        'department_code',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function engineers(): HasMany
    {
        return $this->hasMany(EngineerDetail::class);
    }
}
