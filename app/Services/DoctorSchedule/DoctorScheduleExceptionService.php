<?php

namespace App\Services;

use App\Models\Doctor;

class DoctorScheduleExceptionService
{
    public function __construct(
        protected AvailabilityService $availabilityService,
    ) {}

    public function exception()
    {
        // type=unavailable : all day or time range 
        // unavailable all day: start_time = null end_time = null type = unavailable
        // type = custom_hours: must have time range
        // exceptions shouldn't have overlaps
    }

    public function store(array $data)
    {
        $slotDuration = Doctor::find($data['doctor_id'])->select(['visit_duration'])->get();
        $this->availabilityService->validateTimeRange(
            $slotDuration,
            $data['start_time'],
            $data['end_time']
        );
        //             if (
        //     $startTime &&
        //     $endTime &&
        //     $startTime >= $endTime
        // ) {
        //     throw ValidationException::withMessages([
        //         'start_time' => 'Start time must be before end time.',
        //     ]);
        // }
    }

    public function update(array $data)
    {
        $slotDuration = Doctor::find($data['doctor_id'])->select(['visit_duration'])->get();
        $this->availabilityService->validateTimeRange(
            $slotDuration,
            $data['start_time'],
            $data['end_time']
        );
    }

    // bulkCreateExceptions()

    // deleteException()

    // findByDate()

    // hasOverlap()

    // generateUnavailableRanges()
}
