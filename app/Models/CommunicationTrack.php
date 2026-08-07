<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'allottee_id',
        'sender_type',
        'sender_id',
        'receiver_type',
        'receiver_id',
        'role_id',
        'communication_type',
        'subject',
        'content',
        'ip_address',
        'browser_agent',
        'status',
        'error_message',
    ];
}
