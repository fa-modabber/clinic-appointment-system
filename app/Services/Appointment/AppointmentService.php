<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Enums\Week;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class AppointmentService
{

// create بررسی تداخل زمان، وجود Slot
//update اعتبارسنجی قوانین ویرایش
// cancel بررسی مهلت لغو، وضعیت نوبت
//     getAvailableSlots()

// generateSlots()

// removeReservedSlots()

// applyExceptions()

// applySchedule()

// filterBookingWindow()
    public function store(array $data)
    {
        //     $appointment->end_datetime =
        // $start->copy()->addMinutes($schedule->visit_duration);
    }

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


    public function show(Appointment $appointment): Appointment
    {
        return $appointment;
    }

    public function update(Appointment $appointment, array $data) {}

    public function destroy(Appointment $appointment) {}

    public function cancel(Appointment $appointment) {}


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


    // transaction
    // validation
    // schedule checks
    // availability checks
    // notifications
    // completeAppointment()
    // checkConflict()
    // isSlotAvailable()
    // lockSlot()
    // calculateEndTime()
}
