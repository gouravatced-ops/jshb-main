<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    // Disable updated_at since it's not in the table
    public const UPDATED_AT = null;

    protected $fillable = [
        'application_id',
        'movement_id',
        'user_id',
        'notification_type',
        'subject',
        'message',
        'link',
        'is_read',
        'read_at',
        'is_email_sent',
        'email_sent_at',
        'is_sms_sent',
        'sms_sent_at',
        'is_push_sent',
        'push_sent_at',
        'is_whatsapp_sent',
        'whatsapp_sent_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'sms_sent_at' => 'datetime',
        'push_sent_at' => 'datetime',
        'whatsapp_sent_at' => 'datetime',
        'is_read' => 'boolean',
        'is_email_sent' => 'boolean',
        'is_sms_sent' => 'boolean',
        'is_push_sent' => 'boolean',
        'is_whatsapp_sent' => 'boolean',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function movement()
    {
        return $this->belongsTo(ApplicationMovement::class, 'movement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
