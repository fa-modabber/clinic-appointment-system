<?php

namespace App\Http\Requests\Schedule;

use App\Enums\Week;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorStoreScheduleRequest extends FormRequest
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
            'doctor_id' => [
                'required',
                'integer',
                'exists:doctors,id',
            ],

            'day_of_week' => [
                'required',
                Rule::enum(Week::class),
            ],

            'valid_from' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'valid_until' => [
                'nullable',
                'date',
                'after_or_equal:valid_from',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],

            'visit_duration' => [
                'required',
                'integer',
                'min:5',
                'max:240',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
