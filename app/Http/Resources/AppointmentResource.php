<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ulid' => $this->ulid,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'status' => $this->status,
            'cancelled_at' => $this->cancelled_at,
            'type' => $this->type,

            'doctor' => DoctorResource::make(
                $this->whenLoaded('doctor')
            ),
            'patient' => PatientResource::make(
                $this->whenLoaded('patient')
            )
        ];
    }
}
