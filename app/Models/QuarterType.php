<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuarterType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quarter_type';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'quarter_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quarter_code',
        'quarter_name',
        'quarter_full_name',
        'min_income',
        'max_income',
        'display_order',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'min_income' => 'decimal:2',
        'max_income' => 'decimal:2',
        'display_order' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'display_order' => 0,
        'status' => 1,
    ];


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('quarter_name');
    }


    public function scopeForIncome($query, $income)
    {
        return $query->where(function ($q) use ($income) {
            $q->whereNull('min_income')
              ->orWhere('min_income', '<=', $income);
        })->where(function ($q) use ($income) {
            $q->whereNull('max_income')
              ->orWhere('max_income', '>=', $income);
        });
    }


    public static function findForIncome($income)
    {
        return self::active()->forIncome($income)->first();
    }


    public function isIncomeValid($income)
    {
        $valid = true;
        
        if (!is_null($this->min_income) && $income < $this->min_income) {
            $valid = false;
        }
        
        if (!is_null($this->max_income) && $income > $this->max_income) {
            $valid = false;
        }
        
        return $valid;
    }

    public function getIncomeRangeAttribute()
    {
        if (is_null($this->min_income) && is_null($this->max_income)) {
            return 'Any Income';
        }
        
        if (is_null($this->min_income)) {
            return 'Up to ₹' . number_format($this->max_income, 2) . ' lakh';
        }
        
        if (is_null($this->max_income)) {
            return 'Above ₹' . number_format($this->min_income, 2) . ' lakh';
        }
        
        return '₹' . number_format($this->min_income, 2) . ' - ₹' . number_format($this->max_income, 2) . ' lakh';
    }

    public function getStatusTextAttribute()
    {
        return $this->status == 1 ? 'Active' : 'Inactive';
    }


    public static function validationRules($quarterId = null)
    {
        $rules = [
            'quarter_code' => 'required|string|max:10|unique:quarter_type,quarter_code',
            'quarter_name' => 'required|string|max:100',
            'quarter_full_name' => 'nullable|string|max:200',
            'min_income' => 'nullable|numeric|min:0',
            'max_income' => 'nullable|numeric|min:0|gte:min_income',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ];

        if ($quarterId) {
            $rules['quarter_code'] .= ',' . $quarterId . ',quarter_id';
        }

        return $rules;
    }
}