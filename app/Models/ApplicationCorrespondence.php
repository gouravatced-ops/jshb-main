<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApplicationCorrespondence extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'generated_by_user_id',
        'type',
        'reference_number',
        'subject',
        'content',
        'font_family',
        'status',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by_user_id');
    }

    /**
     * Generate a unique reference number.
     * Example: OO-RNC-080826-A1B2C
     */
    public static function generateReferenceNumber($type, $divisionCode = 'HQ')
    {
        $dateStr = now()->format('dmY');
        
        do {
            $randomStr = strtoupper(Str::random(5));
            $referenceNumber = "{$type}-{$divisionCode}-{$dateStr}-{$randomStr}";
        } while (self::where('reference_number', $referenceNumber)->exists());
        
        return $referenceNumber;
    }
}
