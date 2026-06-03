<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeQuarterFee extends Model
{
    use HasFactory;
    protected $table = 'scheme_quarter_fees';

    public $timestamps = true;

    protected $fillable = [
        'scheme_id',
        'quarter_type_id',
        'application_fee',
        'emd_amount',
    ];

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function quarterType()
    {
        return $this->belongsTo(QuarterType::class);
    }
}
