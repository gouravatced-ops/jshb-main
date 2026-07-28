<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationSetting extends Model
{
    protected $fillable = [
        'role_id',
        'is_email_enabled',
        'is_sms_enabled',
        'is_whatsapp_enabled',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
