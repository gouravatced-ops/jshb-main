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
        'designation',
        'additional_info',
        'date_of_joining',
        'date_of_retirement',
        'date_of_contractual',
        'date_of_deputation',
        'phone_hash',
    ];

    protected $casts = [
        'date_of_joining' => 'date',
        'date_of_retirement' => 'date',
        'date_of_contractual' => 'date',
        'date_of_deputation' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
