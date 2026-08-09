<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status
        ];
    }
}
