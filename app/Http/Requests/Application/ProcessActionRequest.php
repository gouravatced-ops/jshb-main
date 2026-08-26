<?php

namespace App\Http\Requests\Application;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcessActionRequest extends FormRequest
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
        $rules = [
            'action_type' => 'required|string',
            'remarks' => $this->has('is_bypass_request') ? 'nullable|string' : 'required|string',
        ];

        if ($this->input('action_type') === 'forward') {
            if ($this->has('is_bypass_request')) {
                $rules['bypass_reason'] = 'required|string';
            } else {
                $rules['forward_to_user'] = 'required';
            }
        }

        if ($this->input('action_type') === 'send_back') {
            $rules['send_back_to_user'] = 'required';
        }

        // internal_password is conditionally required in the controller for non-allotment approvals
        // We can just add it here for approve actions if it's sent
        if ($this->input('action_type') === 'approve' && $this->has('internal_password')) {
            $rules['internal_password'] = 'required';
        }

        return $rules;
    }
}
