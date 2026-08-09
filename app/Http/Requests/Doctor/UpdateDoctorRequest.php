<?php

namespace App\Http\Requests\Doctor;

use App\Rules\UserRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
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
            UserRule::update($this->doctor->user),
            [
                'clinic_id' => [
                    'sometimes',
                    'required',
                    'integer',
                    Rule::exists('clinics', 'id'),
                ],

                'medical_code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('doctors', 'medical_code')->ignore($this->doctor),
                ],

                'bio' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:5000',
                ],

                'experience_years' => [
                    'sometimes',
                    'nullable',
                    'integer',
                    'between:0,60',
                ],

                'education' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:255',
                ],

                'image' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:255',
                ],

                'booking_days_ahead' => [
                    'sometimes',
                    'required',
                    'integer',
                    'between:1,90',
                ],

                'visit_price' => [
                    'sometimes',
                    'required',
                    'integer',
                    'min:0',
                ],
                'specialties' => [
                    'sometimes',
                    'required',
                    'array',
                    'min:1'
                ],
                'specialties.*' => [
                    'integer',
                    Rule::exists('specialties', 'id')
                ],
            ]
        );
    }
}
