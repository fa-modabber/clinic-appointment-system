<?php

namespace App\Http\Requests\DoctorScheduleException;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\DoctorScheduleExceptionType;
use Illuminate\Validation\Rule;

class StoreDoctorScheduleExceptionRequest extends FormRequest
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

            'date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time'
            ],

            'type' => [
                'required',
                Rule::enum(DoctorScheduleExceptionType::class),
            ],
        ];
    }
}
