<?php

namespace App\Http\Requests\Patient;

use App\Rules\UserRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
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
            UserRule::store(),
            // Patient
            [

                'birth_date' => [
                    'required',
                    'date',
                    'before:today',
                ],

                'gender' => [
                    'required',
                    Rule::in(['female', 'male']),
                ],

                'marital_status' => [
                    'required',
                    Rule::in(['single', 'married', 'divorced']),
                ],
            ]

        );
    }
}
