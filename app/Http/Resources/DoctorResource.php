<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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

            'user' => UserResource::make($this->whenLoaded('user')),

            'clinic' => $this->whenLoaded(
                'clinic',
                fn() => $this->clinic->name
            ),

            'medical_code' => $this->medical_code,
            'bio' => $this->bio,
            'experience_years' => $this->experience_years,
            'education' => $this->education,
            'image' => $this->image,
            'visit_price' => $this->visit_price,

            'specialties' => SpecialtyResource::collection(
                $this->whenLoaded('specialties')
            ),
        ];
    }
}
