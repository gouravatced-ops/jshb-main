<?php

namespace App\Http\Requests\Allottee;

use Illuminate\Contracts\Validation\ValidationRule;

use Illuminate\Foundation\Http\FormRequest;

class Step1Request extends FormRequest
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
            'application_no' => ['required', 'string', 'max:255'],
            'application_day' => ['required', 'string', 'between:1,31'],
            'application_month' => ['required', 'string', 'between:1,12'],
            'application_year' => ['required', 'integer', 'digits:4', 'min:1970', 'max:' . date('Y')],
            'prefix' => ['required', 'string', 'max:255'],
            'allottee_name' => ['required', 'string', 'max:255'],
            'allottee_middle_name' => ['nullable', 'string', 'max:255'],
            'allottee_surname' => ['nullable', 'string', 'max:255'],
            'allottee_prefix_hindi' => ['required', 'string', 'max:100'],
            'allottee_name_hindi' => ['required', 'string', 'max:255'],
            'allottee_middle_hindi' => ['nullable', 'string', 'max:255'],
            'allottee_surname_hindi' => ['nullable', 'string', 'max:255'],
            'relation_prefix' => ['required', 'string', 'max:100'],
            'relation_name' => ['required', 'string', 'max:100'],
            'relation_prefix_hindi' => ['required', 'string', 'max:100'],
            'relation_name_hindi' => ['required', 'string', 'max:100'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'allottee_gender' => ['nullable', 'string', 'max:20'],
            'pan_card_number' => ['nullable', 'string', 'max:20'],
            'aadhar_card_number' => ['nullable', 'string', 'max:20'],
            'allottee_category' => ['nullable', 'string', 'max:100'],
            'allottee_category_hindi' => ['nullable', 'string', 'max:200'],
            'allottee_religion' => ['nullable', 'string', 'max:100'],
            'allottee_nationality' => ['nullable', 'string', 'max:100'],
            'date_of_birth_day' => ['required', 'string', 'between:1,31'],
            'date_of_birth_month' => ['required', 'string', 'between:1,12'],
            'date_of_birth_year' => ['required', 'integer', 'digits:4'],
            'current_age' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'application_year.max' => 'Application year cannot be greater than current year.',
        ];
    }
}
