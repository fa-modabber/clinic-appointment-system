<?php

namespace App\Http\Requests\Patient;

use App\Rules\UserRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            UserRule::update($this->patient->user),
            // Patient
            [

                'birth_date' => [
                    'sometimes',
                    'required',
                    'date',
                    'before:today',
                ],

                'gender' => [
                    'sometimes',
                    'required',
                    Rule::in(['female', 'male']),
                ],

                'marital_status' => [
                    'sometimes',
                    'required',
                    Rule::in(['single', 'married', 'divorced']),
                ],
            ]
        );
    }
}
