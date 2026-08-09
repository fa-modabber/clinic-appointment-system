<?php

namespace App\Services;

use App\Enums\DoctorScheduleExceptionType;
use App\Enums\Week;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleException;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AvailabilityService
{
    public function validateTimeRange(
        int $slotDuration,
        Carbon $startTime,
        Carbon $endTime
    ): void {
        if ($endTime->lessThanOrEqualTo($startTime)) {
            throw ValidationException::withMessages([
                'end_time' => ['End time must be after start time.'],
            ]);
        }
        $duration = $startTime->diffInMinutes($endTime);

        if ($duration % $slotDuration !== 0) {
            throw ValidationException::withMessages([
                'end_time' => ['Time range must be divisible by visit duration.'],
            ]);
        }
    }

    public function availableSlotsByDoctor(Doctor $doctor)
    {
        $today = today();
        $lastDay = today()->copy()->addDays($doctor->booking_days_ahead - 1);
        $slotDuration = $doctor->visit_duration;

        $schedules = $this->loadSchedules($doctor->id);
        $exceptions = $this->loadExceptions($doctor->id);
        $appointments = $this->loadAppointments($doctor->id);

        $availableSlots = collect();

        for (
            $date = $today->copy();
            $date <= $lastDay;
            $date->addDay()
        ) {
            // generate base slots from schedules
            $baseSlots = $this->generateBaseSlots(
                $date,
                $schedules,
                $slotDuration
            );

            // generate exception slots
            [
                'extraSlots' => $extraSlots,
                'unavailableSlots' => $unavailableSlots,
            ] = $this->calculateExceptionSlots(
                $date,
                $exceptions,
                $slotDuration
            );

            // generate slots with appointments
            $bookedSlots = $this->generateBookedSlots(
                $date,
                $appointments
            );

            $slots = $baseSlots
                ->merge($extraSlots)
                ->reject(fn($slot) => $unavailableSlots->contains($slot))
                ->reject(fn($slot) => $bookedSlots->contains($slot))
                ->values()
                ->unique()
                ->sort();

            $availableSlots->push([
                'date' => $date->toDateString(),
                'slots' => $slots
            ]);
        }

        return $availableSlots;
    }

    private function loadSchedules(int $doctorId)
    {
        return DoctorSchedule::query()
            ->forDoctor($doctorId)
            ->active()
            ->get()
            ->groupBy('day_of_week');
    }

    private function loadExceptions(int $doctorId)
    {
        return DoctorScheduleException::query()
            ->forDoctor($doctorId)
            ->get()
            ->groupBy('date');
    }

    private function loadAppointments(int $doctorId)
    {
        return Appointment::query()
            ->forDoctor($doctorId)
            ->upcoming()
            ->get()
            ->groupBy('date');
    }

    private function generateBaseSlots(
        Carbon $date,
        Collection $schedules,
        int $slotDuration
    ): Collection {
        //find week day
        $dayOfWeek = Week::fromCarbon($date);

        //retrieve schedule
        $daySchedules = $schedules->get($dayOfWeek, collect());

        // generate slots from schedules
        $baseSlots = collect();
        foreach ($daySchedules as $schedule) {
            $baseSlots = $baseSlots->merge(
                $this->generateSlots(
                    $schedule->start_time,
                    $schedule->end_time,
                    $slotDuration
                )
            );
        }

        return $baseSlots;
    }

    private function generateSlots(
        Carbon $start,
        Carbon $end,
        int $slotDuration
    ): Collection {
        $slots = collect();

        while ($start->copy()->addMinutes($slotDuration) <= $end) {
            $slots->push($start->format('H:i'));
            $start->addMinutes($slotDuration);
        }

        return $slots;
    }

    private function calculateExceptionSlots(
        Carbon $date,
        Collection $exceptions,
        int $slotDuration
    ) {
        $dayExceptions = $exceptions->get(
            $date->toDateString(),
            collect()
        );
        $extraSlots = collect();
        $unavailableSlots = collect();
        foreach ($dayExceptions as $exception) {
            if (
                $exception->type ===
                DoctorScheduleExceptionType::UNAVAILABLE
            ) {
                $unavailableSlots = $unavailableSlots->merge($this->generateSlots(
                    $exception->start_time,
                    $exception->end_time,
                    $slotDuration
                ));
            }

            if (
                $exception->type ===
                DoctorScheduleExceptionType::CUSTOM_HOURS
            ) {
                $extraSlots = $extraSlots->merge($this->generateSlots(
                    $exception->start_time,
                    $exception->end_time,
                    $slotDuration
                ));
            }
        }

        return [
            'unavailableSlots' => $unavailableSlots,
            'extraSlots' => $extraSlots
        ];
    }

    private function generateBookedSlots(
        Carbon $date,
        Collection $appointments,
    ): Collection {
        $dayAppointments = $appointments->get(
            $date->toDateString(),
            collect()
        );
        $bookedSlots = $dayAppointments
            ->pluck('start_time')
            ->map(fn(Carbon $time) => $time->format('H:i'));

        return $bookedSlots;
    }
}
