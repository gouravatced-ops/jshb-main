<?php

namespace App\Http\Requests\Allottee;

use Illuminate\Contracts\Validation\ValidationRule;

use Illuminate\Foundation\Http\FormRequest;

class Step3Request extends FormRequest
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
        return [
            'applicant_id' => 'required|integer|exists:adms_allottees.allottees,id',
            'final_submission' => 'required|boolean',
        ];
    }
}
