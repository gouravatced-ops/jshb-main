<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotteeSiteVerification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function allottee()
    {
        return $this->belongsTo(Allottee::class);
    }
}
