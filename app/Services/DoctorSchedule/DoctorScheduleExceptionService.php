<?php

namespace App\Services;

use App\Enums\DoctorScheduleExceptionType;
use App\Enums\Week;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class DoctorScheduleExceptionService
{
    public function __construct(
        protected AvailabilityService $availabilityService,
    ) {}

    public function store(array $data): DoctorScheduleException
    {
        $doctor = Doctor::findOrFail($data['doctor_id']);

        $this->commonValidation($data);

        match ($data['type']) {
            DoctorScheduleExceptionType::UNAVAILABLE =>
            $this->validateUnavailableException($doctor, $data),

            DoctorScheduleExceptionType::CUSTOM_HOURS =>
            $this->validateCustomHoursException($doctor, $data),
        };

        // validation: no overlapping exception
        $this->validateNoOverlappingException(
            $doctor,
            $data['date'],
            $data['start_time'],
            $data['end_time']
        );

        return DoctorScheduleException::create($data);
    }

    public function update(
        DoctorScheduleException $exception,
        array $data
    ) {
        $this->commonValidation($data);

        match ($exception->type) {
            DoctorScheduleExceptionType::UNAVAILABLE =>
            $this->validateUnavailableException(
                $exception->doctor,
                $data
            ),

            DoctorScheduleExceptionType::CUSTOM_HOURS =>
            $this->validateCustomHoursException(
                $exception->doctor,
                $data
            ),
        };

        // validation: no overlapping exception
        $this->validateNoOverlappingException(
            $exception->doctor,
            $data['date'],
            $data['start_time'],
            $data['end_time'],
            $exception
        );

        return DoctorScheduleException::update($data);
    }

    private function commonValidation(
        array $data
    ) {
        $this->validateDate($data['date']);

        $this->availabilityService->validateTimeRange(
            $data['start_time'],
            $data['end_time']
        );
    }

    private function validateDate(Carbon $date): void
    {
        if ($date->lt(today())) {
            throw ValidationException::withMessages([
                'date' => ['Date cannot be before today.'],
            ]);
        }
    }

    private function validateUnavailableException(
        Doctor $doctor,
        array $data
    ) {
        // validation: containing schedule exists?
        $schedule = $this->findContainingSchedule(
            $doctor,
            $data['date'],
            $data['start_time'],
            $data['end_time']
        );

        // validation: new interval divisible by visit_duration?
        $this->validateSlotAlignment(
            $doctor,
            $schedule,
            $data['start_time'],
            $data['end_time']
        );

        // validation: no conflicting appointment?
        $this->validateNoConflictingAppointment(
            $doctor,
            $data['date'],
            $data['start_time'],
            $data['end_time']
        );
    }

    private function validateCustomHoursException(
        Doctor $doctor,
        array $data
    ) {
        $this->availabilityService->divisibleBySlotDuration(
            $doctor->visit_duration,
            $data['start_time'],
            $data['end_time']
        );
    }

    private function findContainingSchedule(
        Doctor $doctor,
        Carbon $date,
        Carbon $startTime,
        Carbon $endTime
    ) {
        $dayOfWeek = Week::fromCarbon($date);
        $schedules = DoctorSchedule::query()
            ->forDoctor($doctor->id)
            ->active()
            ->weekday($dayOfWeek)
            ->get();

        $targetedSchedule = $schedules
            ->first(fn(DoctorSchedule $schedule)
            => $schedule->start_time <= $startTime &&
                $schedule->end_time >= $endTime);

        if (!$targetedSchedule) {
            throw ValidationException::withMessages([
                'start_time' =>
                'There is no schedule containing the specified time range.',
            ]);
        }

        return $targetedSchedule;
    }

    private function validateSlotAlignment(
        Doctor $doctor,
        DoctorSchedule $schedule,
        Carbon $startTime,
        Carbon $endTime
    ) {
        $slotDuration = $doctor->visit_duration;

        $this->availabilityService->divisibleBySlotDuration(
            $slotDuration,
            $schedule->start_time,
            $startTime
        );
        $this->availabilityService->divisibleBySlotDuration(
            $slotDuration,
            $endTime,
            $schedule->end_time
        );
    }

    private function validateNoOverlappingException(
        Doctor $doctor,
        Carbon $date,
        Carbon $startTime,
        Carbon $endTime,
        ?DoctorScheduleException $exception = null
    ): void {
        $query = DoctorScheduleException::query()
            ->forDoctor($doctor->id)
            ->active()
            ->forDate($date)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);

        if ($exception) {
            $query->where('id', '!=', $exception->id);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'start_time' => [
                    'The specified time range overlaps with another exception.'
                ],
            ]);
        }
    }

    private function validateNoConflictingAppointment(
        Doctor $doctor,
        Carbon $date,
        Carbon $startTime,
        Carbon $endTime
    ): void {
        $hasConflict = Appointment::query()
            ->forDoctor($doctor->id)
            ->forDate($date)
            ->upcoming()
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($hasConflict) {
            throw ValidationException::withMessages([
                'start_time' => [
                    'The specified time range conflicts with an existing appointment.'
                ],
            ]);
        }
    }

    public function index() {}



    public function destroy(DoctorScheduleException $exception) {}

    public function show(DoctorScheduleException $exception) {}

    public function activate(
        DoctorScheduleException $exception
    ): DoctorScheduleException {
        $exception->update([
            'is_active' => true,
        ]);

        return $exception->refresh();
    }

    public function deactivate(
        DoctorScheduleException $exception
    ): DoctorScheduleException {
        $exception->update([
            'is_active' => false,
        ]);

        return $exception->refresh();
    }

    public function exception()
    {
        // type=unavailable : all day or time range 
        // unavailable all day: start_time = null end_time = null type = unavailable
        // type = custom_hours: must have time range
        // exceptions shouldn't have overlaps
    }

    private function hasAppointmentsInRange(
        Doctor $doctor,
        Carbon $date,
        Carbon $startTime,
        Carbon $endTime
    ): bool {
        return true;
        // Appointment start < Exception end
        //     AND
        // Appointment end > Exception start
    }

    private function hasOverlappingExceptions(
        DoctorScheduleException $exception
    ): bool {
        return true;
    }

    private function hasConflictingAppointments() {}

    // bulkCreateExceptions()

    // deleteException()

    // findByDate()

    // hasOverlap()

    // generateUnavailableRanges()
}
