<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Models\Doctor;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;

class AvailabilityController extends ApiController
{
    public function __construct(
        protected AvailabilityService $availabilityService,
    ) {}

    public function availableSlotsByDoctor(Doctor $doctor)
    {
        $data = $this->availabilityService->availableSlotsByDoctor($doctor);
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            $data
        );
    }

    // nearestAvailableSlot()
    // nextAvailableDay()
    // availableDays()
    // monthlyAvailability()
    // weeklyAvailability()
}
