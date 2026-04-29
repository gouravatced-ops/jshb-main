<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\EncryptedRouteKey;

class BlockList extends Model
{
    use SoftDeletes, EncryptedRouteKey;

    protected $table = 'block_lists';

    protected $fillable = [
        'district_id',
        'block_name_eng',
        'block_name_hn',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
