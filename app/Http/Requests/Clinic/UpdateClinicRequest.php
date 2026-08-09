<?php

namespace App\Http\Requests\Clinic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClinicRequest extends FormRequest
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
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'address' => [
                'sometimes',
                'required',
                'string',
                'max:5000',
            ],

            'phone' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'latitude' => [
                'sometimes',
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'sometimes',
                'required',
                'numeric',
                'between:-180,180',
            ],
        ];
    }
}
