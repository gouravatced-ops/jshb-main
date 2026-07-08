<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationNote extends Model
{
    use HasFactory;

    protected $table = 'application_notes';

    protected $fillable = [
        'application_id',
        'movement_id',
        'user_id',
        'role_id',
        'note_type',
        'remarks',
        'signature',
        'signature_type',
        'signature_date',
        'is_confidential',
        'is_public',
    ];

    protected $casts = [
        'signature_date' => 'datetime',
        'is_confidential' => 'boolean',
        'is_public' => 'boolean',
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

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
