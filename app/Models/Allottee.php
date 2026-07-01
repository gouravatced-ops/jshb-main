<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\EncryptedRouteKey;

class Allottee extends Model
{
    use HasFactory, EncryptedRouteKey;
    protected $connection = 'adms_allottees';
    protected $table = 'allottees';
    public $timestamps = true;

    protected $fillable = [
        'division_id',
        'subdivision_id',
        'pcategory_id',
        'property_type_id',
        'p_sub_type_id',
        'quarter_id',
        'property_number',
        'username',
        'password',
        'scheme_id',
        'application_no',
        'application_day',
        'application_month',
        'application_year',
        'allotment_no',
        'allotment_day',
        'allotment_month',
        'allotment_year',
        'prefix',
        'allottee_name',
        'allottee_middle_name',
        'allottee_surname',
        'allottee_prefix_hindi',
        'allottee_name_hindi',
        'allottee_middle_hindi',
        'allottee_surname_hindi',
        'allottee_relation_type',
        'relation_name',
        'marital_status',
        'allottee_gender',
        'pan_card_number',
        'aadhar_card_number',
        'allottee_category',
        'allottee_religion',
        'allottee_nationality',
        'date_of_birth_day',
        'date_of_birth_month',
        'date_of_birth_year',
        'allottee_remarks',
        'current_step',
        'step_remarks',
        'payment_option',
        'allottee_create_date',
        'create_ip_address',
        'update_ip_address',
        'allottee_document_path',
        'is_step_completed',
        'updated_by',
        'created_by',
        'deleted_at',
    ];

    // Parent relationship (the parent of this allottee)
    public function parent()
    {
        return $this->belongsTo(Allottee::class, 'parent_id');
    }

    // Children relationship (allottees that have this as parent)
    public function children()
    {
        return $this->hasMany(Allottee::class, 'parent_id');
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }

    public function schemeFinance()
    {
        return $this->belongsTo(SchemeFinancial::class, 'scheme_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function subDivision()
    {
        return $this->belongsTo(SubDivision::class, 'subdivision_id');
    }

    public function propertyCategory()
    {
        return $this->belongsTo(PropertyCategory::class, 'pcategory_id');
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function propertySubType()
    {
        return $this->belongsTo(PropertyMainType::class, 'property_subtype_id');
    }

    public function quarterType()
    {
        return $this->belongsTo(QuarterType::class, 'quarter_id');
    }

    public function allotProFinDetail()
    {
        return $this->hasOne(AllotteePropertyFinDetail::class, 'allottee_id', 'id');
    }

    public function alloteeAdresses()
    {
        return $this->hasOne(AllotteesContactDetail::class, 'allottee_id', 'id');
    }

    public function nomineesBank()
    {
        return $this->hasOne(AllotteeNomineeBankDetail::class, 'allottee_id', 'id');
    }

    public function accountLedger()
    {
        return $this->hasOne(AllotteeEmiLedger::class, 'allottee_id', 'id');
    }

    public function documentData()
    {
        return $this->hasMany(AllotteeDocument::class, 'allottee_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function processSteps()
    {
        return $this->hasMany(AllotteeProcessStep::class, 'allottee_id', 'id');
    }

    public function generatedDocument()
    {
        return $this->hasMany(AllotteeGeneratedDocument::class, 'allottee_id', 'id');
    }

    public function allotteeOrders()
    {
        return $this->hasMany(AllotteePaymentOrder::class, 'allottee_id', 'id');
    }

    public function allotteeTransaction()
    {
        return $this->hasMany(AllotteeTransaction::class, 'allottee_id', 'id');
    }

    public function emiAccount()
    {
        return $this->hasMany(AllotteeEmiAccount::class, 'allottee_id', 'id');
    }

    public function emiSchedule()
    {
        return $this->hasMany(AllotteeEmiSchedule::class, 'allottee_id', 'id');
    }

    public function emiDemand()
    {
        return $this->hasMany(AllotteeMonthlyDemand::class, 'allottee_id', 'id');
    }

    public function siteVerification()
    {
        return $this->hasOne(AllotteeSiteVerification::class, 'allottee_id', 'id');
    }

    public static function generateUniquePropertyNumber(): string
    {
        do {
            $propertyNumber = chr(rand(65, 72))
                . '-'
                . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (
            self::where('property_number', $propertyNumber)->exists()
        );

        return $propertyNumber;
    }
}
