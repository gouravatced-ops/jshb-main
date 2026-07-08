<?php
namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AllotteeProcessStep extends Model
{
    use HasFactory;
    protected $connection = 'adms_allottees';
    protected $table = 'allottee_process_steps';
    
    // STATUS
    public const STATUS_LOCKED    = 'locked';
    public const STATUS_PENDING   = 'pending';
    public const STATUS_COMPLETED = 'completed';
    
    // FILLABLE
    protected $fillable = [
        'allottee_id',
        'menu_order',
        'step_order',
        'icons',
        'menu_key',
        'sub_menu_key',
        'process_group',
        'step_no',
        'title',
        'description',
        'blade',
        'status',
        'is_active',
        'started_at',
        'completed_at',
        'due_date',
        'remarks',
        'meta',
        'completed_by',
        'created_by',
        'updated_by',
    ];
    
    // CASTS
    protected $casts = [
        'meta'          => 'array',
        'is_active'     => 'boolean',
        'started_at'    => 'datetime',
        'completed_at'  => 'datetime',
        'due_date'      => 'datetime',
        'menu_order'    => 'integer',
        'step_order'    => 'integer',
        'step_no'       => 'integer',
        'allottee_id'   => 'integer',
        'completed_by'  => 'integer',
        'created_by'    => 'integer',
        'updated_by'    => 'integer',
    ];
    
    // APPENDS
    protected $appends = [
        'is_completed',
        'is_locked',
        'is_pending',
    ];
    
    // RELATIONSHIPS
    public function allottee()
    {
        return $this->belongsTo(
            Allottee::class,
            'allottee_id'
        );
    }

    public function completedBy()
    {
        return $this->belongsTo(
            User::class,
            'completed_by'
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
    
    // SCOPES
    public function scopeActive($query)
    {
        return $query->where(
            'is_active',
            true
        );
    }

    public function scopeCompleted($query)
    {
        return $query->where(
            'status',
            self::STATUS_COMPLETED
        );
    }

    public function scopePending($query)
    {
        return $query->where(
            'status',
            self::STATUS_PENDING
        );
    }

    public function scopeLocked($query)
    {
        return $query->where(
            'status',
            self::STATUS_LOCKED
        );
    }

    public function scopeMenu($query, string $menuKey)
    {
        return $query->where(
            'menu_key',
            $menuKey
        );
    }

    public function scopeSubMenu($query, string $subMenuKey)
    {
        return $query->where(
            'sub_menu_key',
            $subMenuKey
        );
    }
    
    // ACCESSORS
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function getIsLockedAttribute(): bool
    {
        return $this->status === self::STATUS_LOCKED;
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    // HELPERS
    public function markAsCompleted(
        ?int $userId = null,
        ?string $remarks = null
    ): bool {
        return $this->update([
            'status'       => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'completed_by' => $userId,
            'remarks'      => $remarks,
        ]);
    }

    public function markAsPending(): bool
    {
        return $this->update([
            'status' => self::STATUS_PENDING,
        ]);
    }

    public function markAsLocked(): bool
    {
        return $this->update([
            'status' => self::STATUS_LOCKED,
        ]);
    }

    public function activate(): bool
    {
        return $this->update([
            'is_active' => true,
        ]);
    }

    public function deactivate(): bool
    {
        return $this->update([
            'is_active' => false,
        ]);
    }
    
    // STATIC HELPERS
    public static function unlockNextStep(
        int $allotteeId,
        int $currentStepNo
    ): void {
        self::where(
            'allottee_id',
            $allotteeId
        )
        ->where(
            'step_no',
            $currentStepNo + 1
        )
        ->update([
            'status' => self::STATUS_PENDING
        ]);
    }
    
    public static function completeStep(
        int $allotteeId,
        string $menuKey,
        ?string $subMenuKey = null,
        ?int $userId = null
    ): void {
        self::where([
            'allottee_id' => $allotteeId,
            'menu_key'    => $menuKey,
            'sub_menu_key'=> $subMenuKey,
        ])->update([
            'status'       => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'completed_by' => $userId,
        ]);
    }
}