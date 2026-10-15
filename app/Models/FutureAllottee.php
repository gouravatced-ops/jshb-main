<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FutureAllotteeContactDetail;
use App\Models\FutureAllotteeTransaction;
use App\Models\Scheme;
use App\Models\Division;
use App\Models\SubDivision;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use App\Models\QuarterType;
use App\Models\Allottee;
use App\Models\Application;

class FutureAllottee extends Model
{
    use HasFactory;

    protected $connection = 'adms_allottees';
    protected $table = 'future_allottees';

    protected $fillable = [
        'reference_portal_id',
        'application_no',
        'application_day',
        'application_month',
        'application_year',
        'scheme_id',
        'division_id',
        'subdivision_id',
        'pcategory_id',
        'property_type_id',
        'p_sub_type_id',
        'quarter_id',
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
        'relation_prefix_hindi',
        'relation_name_hindi',
        'marital_status',
        'allottee_gender',
        'pan_card_number',
        'aadhar_card_number',
        'allottee_category',
        'allottee_category_hindi',
        'allottee_religion',
        'allottee_nationality',
        'date_of_birth_day',
        'date_of_birth_month',
        'date_of_birth_year',
        'email',
        'mobile',
        'remarks',
        'sync_status',
        'synced_allottee_id',
        'synced_application_id',
        'synced_at',
        'error_log',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
        'date_of_birth_day' => 'integer',
        'date_of_birth_month' => 'integer',
        'date_of_birth_year' => 'integer',
    ];

    public function contactDetail()
    {
        return $this->hasOne(FutureAllotteeContactDetail::class, 'future_allottee_id');
    }

    public function transaction()
    {
        return $this->hasOne(FutureAllotteeTransaction::class, 'future_allottee_id');
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
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

    public function quarterType()
    {
        return $this->belongsTo(QuarterType::class, 'quarter_id', 'quarter_id');
    }

    public function syncedAllottee()
    {
        return $this->belongsTo(Allottee::class, 'synced_allottee_id');
    }

    public function syncedApplication()
    {
        return $this->belongsTo(Application::class, 'synced_application_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->allottee_name,
            $this->allottee_middle_name,
            $this->allottee_surname,
        ])));
    }
}
