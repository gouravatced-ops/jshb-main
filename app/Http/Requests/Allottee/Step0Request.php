<?php

namespace App\Http\Requests\Allottee;

use Illuminate\Contracts\Validation\ValidationRule;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Allottee;

class Step0Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = null;
        if ($this->filled('applicant_id')) {
            $applicant = Allottee::find($this->applicant_id);
            if ($applicant) {
                $userId = $applicant->user_id;
            }
        }

        return [
            'applicant_id'     => 'nullable|integer|exists:adms_allottees.allottees,id',
            'email'            => 'required|email|max:255|unique:adms_allottees.users,email' . ($userId ? ',' . $userId : ''),
            'payment_amount'   => 'required|numeric|min:0.01',
            'payment_day'      => 'required|between:1,31',
            'payment_month'    => 'required|between:1,12',
            'payment_year'     => 'required|max:' . now()->year,
            'payment_utr_no'   => 'nullable|string|max:255',
            'payment_receipt'  => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'division_id'      => 'required|string',
            'subdivision_id'   => 'required|string',
            'pcategory_id'     => 'required|string',
            'property_type_id' => 'required|string',
            'quarter_id'       => 'required|string',
            'scheme_id'        => 'required|integer|exists:schemes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'scheme_id.exists' => 'Selected scheme is invalid.',
        ];
    }
}
