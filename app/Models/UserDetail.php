<?php

namespace App\Models;

use App\Support\SensitiveData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'organization',
        'designation',
        'additional_info',
        'anniversary_date',
        'date_of_birth',
        'spouse_name',
        'no_of_children',
        'boys',
        'girls',
        'phone_hash',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
