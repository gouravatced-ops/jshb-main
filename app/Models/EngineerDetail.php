<?php

namespace App\Models;

use App\Support\SensitiveData;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use App\Traits\EncryptedRouteKey;

class EngineerDetail extends Model
{
    use SoftDeletes, EncryptedRouteKey;

    protected $fillable = [
        'user_id',
        'current_organization_id',
        'parent_organization_id',
        'district_id',
        'block_id',
        'division_id',
        'sub_division_id',
        'post_type_id',
        'department_id',
        'employee_name',
        'employee_hindi_name',
        'state_government_engineer_id',
        'date_of_birth',
        'anniversary_date',
        'spouse_name',
        'no_of_children',
        'aadhar_no',
        'pan_card_no',
        'state_government_engineer_id_hash',
        'aadhar_no_hash',
        'pan_card_no_hash',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'anniversary_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'current_organization_id');
    }

    public function parentOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'parent_organization_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(BlockList::class, 'block_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function subDivision(): BelongsTo
    {
        return $this->belongsTo(SubDivision::class, 'sub_division_id');
    }

    public function postType(): BelongsTo
    {
        return $this->belongsTo(PostType::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function requisitions(): HasMany
    {
        return $this->hasMany(GuestHouseRequisition::class, 'engineer_detail_id');
    }

    protected function stateGovernmentEngineerId(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => SensitiveData::decrypt($value),
            set: fn (?string $value) => [
                'state_government_engineer_id' => SensitiveData::encrypt($value),
                'state_government_engineer_id_hash' => SensitiveData::hash($value),
            ],
        );
    }

    protected function aadharNo(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => SensitiveData::decrypt($value),
            set: fn (?string $value) => [
                'aadhar_no' => SensitiveData::encrypt($value),
                'aadhar_no_hash' => SensitiveData::hash($value),
            ],
        );
    }

    protected function panCardNo(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => SensitiveData::decrypt($value),
            set: fn (?string $value) => [
                'pan_card_no' => SensitiveData::encrypt($value),
                'pan_card_no_hash' => SensitiveData::hash($value),
            ],
        );
    }

    public function getMaskedStateGovernmentEngineerIdAttribute(): string
    {
        return SensitiveData::mask($this->getRawOriginal('state_government_engineer_id'));
    }

    public function getMaskedAadharNoAttribute(): string
    {
        return SensitiveData::mask($this->getRawOriginal('aadhar_no'));
    }

    public function getMaskedPanCardNoAttribute(): string
    {
        return SensitiveData::mask($this->getRawOriginal('pan_card_no'));
    }

    public function getEncryptedRouteKeyAttribute(): string
    {
        return Crypt::encryptString((string) $this->getKey());
    }
}
