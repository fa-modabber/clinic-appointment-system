<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Enums\Week;
use App\Events\AppointmentCreated;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Exception;
use Illuminate\Support\Carbon;

class AppointmentService
{
    public function __construct(
        protected AvailabilityService $availabilityService,
    ) {}

    public function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['status'])) {
            $query->status($filters['status']);
        }

        if (!empty($filters['date'])) {
            $query->date($filters['date']);
        } elseif (!empty($filters['from']) && !empty($filters['to'])) {
            $query->between($filters['from'], $filters['to']);
        }

        return $query;
    }

    private function getAppointments(Builder $query, array $filters)
    {
        return $this->applyFilters($query, $filters)->paginate();
    }

    public function index(array $filters)
    {
        return $this->getAppointments(
            Appointment::query(),
            $filters
        );
    }

    public function doctorSelfAppointments(User $user, array $filters)
    {
        if (!$user->isDoctor()) {
            throw new Exception('you are not a doctor');
        }

        return $this->getAppointments(
            Appointment::forDoctor($user->doctor),
            $filters
        );
    }

    public function appointmentsByDoctor(Doctor $doctor, array $filters)
    {
        return $this->getAppointments(
            Appointment::forDoctor($doctor),
            $filters
        );
    }

    public function patientSelfAppointments(User $user, array $filters)
    {
        if (!$user->patient()->exists()) {
            throw new Exception('you are not a patient');
        }

        return $this->getAppointments(
            Appointment::forPatient($user->patient),
            $filters
        );
    }

    public function appointmentsByPatient(Patient $patient, array $filters)
    {
        return $this->getAppointments(
            Appointment::forPatient($patient),
            $filters
        );
    }

    public function updateStatus(
        Appointment $appointment,
        string $status
    ): Appointment {
        if ($appointment->status === $status) {
            throw ValidationException::withMessages([
                'status' => ['The provided status is the same as before'],
            ]);
        }

        $appointment->update([
            'status'
        ]);

        return $appointment->fresh();
    }

    public function hasUpcomingAppointments(
        Doctor $doctor,
        ?Week $weekDay = null
    ): bool {
        $query = Appointment::query()
            ->forDoctor($doctor)
            ->upcoming();

        if ($weekDay) {
            $query->weekday($weekDay);
        }

        return $query->exists();
    }

    public function show(Appointment $appointment): Appointment
    {
        return $appointment->load(['doctor', 'patient']);
    }

    // check conflict
    public function store(array $data)
    {
        $doctor = Doctor::findOrFail($data['doctor_id']);

        $date = Carbon::parse($data['date']);
        $startTime = Carbon::parse(
            $data['date'] . ' ' . $data['start_time']
        );

        $slotDuration = $doctor->visit_duration;

        // Check if the selected slot is still available
        $isAvailable = $this->availabilityService->isSlotAvailable(
            $doctor,
            $date,
            $startTime,
            $slotDuration
        );

        if (!$isAvailable) {
            throw ValidationException::withMessages([
                'start_time' => ['The selected time slot is no longer available.'],
            ]);
        }

        $appointment = Appointment::create([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $doctor->id,
            'date' => $date->toDateString(),
            'start_time' => $startTime->format('H:i:s'),
            'type' => $data['type'],
        ]);

        AppointmentCreated::dispatch($appointment);

        return $appointment;
    }

    // update validation
    public function update(Appointment $appointment, array $data) {}

    // check for deadline of canceling
    //check for status of appointment
    public function cancel(Appointment $appointment) {}

    public function destroy(Appointment $appointment) {}





    // -------------------------
    // Availability & Scheduling
    // -------------------------

    // getAvailableSlots()
    // generateSlots()
    // removeReservedSlots()
    // applyExceptions()
    // applySchedule()
    // filterBookingWindow()

    // checkConflict()
    // isSlotAvailable()
    // lockSlot()
    // calculateEndTime()

    // -------------------------
    // Appointment Lifecycle
    // -------------------------

    // completeAppointment()
}
