<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allottee extends Model
{
    use HasFactory;
    protected $table = 'allottees';
    public $timestamps = true;

    protected $fillable = [
        'register_id',
        'register_file_id',
        'division_id',
        'subdivision_id',
        'pcategory_id',
        'property_type_id',
        'quarter_id',
        'property_number',
        'username',
        'password',
        'cedjshb',
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
        'age_number_of_birth_application',
        'age_number_of_birth_application_hindi',
        'age_word_of_birth_application',
        'age_word_hindi_of_birth_application',
        'date_of_birth_day',
        'date_of_birth_month',
        'date_of_birth_year',
        'remarks_for_dob',
        'no_of_files',
        'no_of_supplement',
        'json_pages',
        'total_pages',
        'file_remarks',
        'parent_id',
        'current_step',
        'is_trans_entry_completed',
        'is_free_hold_completed',
        'name_transfer_status',
        'free_hold_status',
        'step_remarks',
        'is_first_time_register',
        'is_earlier_cancelled',
        'allottee_create_date',
        'create_ip_address',
        'update_ip_address',
        'allottee_document_path',
        'is_step_completed',
        'sub_admin_allottee_verify',
        'sub_admin_remarks',
        'sub_admin_checked_date',
        'divisional_approval',
        'divisional_remaks',
        'divisional_approved_date',
        'divisional_approved_by',
        'updated_by',
        'created_by',
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

    // Recursive children with nested relationships
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    // Recursive parent chain
    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
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
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function Usercreator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function Userupdater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function jointAllottees()
    {
        return $this->hasMany(JointAllottee::class, 'allottee_id');
    }

    public function registration()
    {
        return $this->belongsTo(RegistrationFile::class, 'register_id');
    }

    public function masterDocuments()
    {
        return $this->hasMany(AllotteeMasterDocument::class, 'allottee_id', 'id')->orderBy('file_label', 'asc');
    }
}
