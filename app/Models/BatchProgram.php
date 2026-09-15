<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchProgram extends Model
{
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(BatchProgramDetail::class, 'batch_program_id', 'id');
    }
}
