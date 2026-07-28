<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\ApplicationMovement;
use App\Models\User;
use App\Models\Role;

class ApplicationNote extends Model
{
    use HasFactory;

    protected $connection = 'adms_jshb';
    protected $table = 'application_notes';

    protected $fillable = [
        'application_id',
        'movement_id',
        'user_id',
        'role_id',
        'note_type',
        'remarks',
        'font_family',
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

    public function getRemarksAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        // Fix orphaned <li> tags (e.g., from copy-pasting into Summernote)
        // If there are <li> tags but NO <ul> or <ol> tags, wrap contiguous <li> blocks in a <ul>.
        // This prevents mPDF "Undefined array key list_style_type" crash and fixes UI rendering.
        if (stripos($value, '<li') !== false && stripos($value, '<ul') === false && stripos($value, '<ol') === false) {
            $value = preg_replace('/(<li[^>]*>.*?<\/li>\s*)+/is', '<ul style="list-style-type: disc; margin: 0; padding-left: 20px;">$0</ul>', $value);
        }

        return $value;
    }

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
