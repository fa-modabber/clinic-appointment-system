<?php

namespace App\Services;

use App\Enums\Week;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;


class DoctorScheduleService
{
    public function __construct(
        protected AvailabilityService $availabilityService,
        protected AppointmentService $appointmentService
    ) {}

    public function store(array $data)
    {
        $doctor = Doctor::findOrFail($data['doctor_id']);
        $slotDuration = $doctor->visit_duration;
        $this->availabilityService->validateTimeRange(
            $slotDuration,
            $data['start_time'],
            $data['end_time']
        );

        $overlap = DoctorSchedule::query()
            ->forDoctor($doctor->id)
            ->weekday($data['day_of_week'])
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->exists();

        if ($overlap) {
            throw new Exception('time overlap with existing schedule');
        }

        $schedule = DoctorSchedule::create(
            [
                'doctor_id' => $data['doctor_id'],
                'day_of_week' => $data['day_of_week'],
                'valid_from' => $data['valid_from'],
                'valid_until' => $data['valid_until'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time']
            ]
        );

        return $schedule->load('doctor');
    }

    public function update(DoctorSchedule $schedule, array $data): DoctorSchedule
    {
        //check for upcoming appointments
        $doctor = $schedule->doctor;
        $currentWeekday = $schedule->day_of_week;
        $appointmentsQuery = Appointment::query()
            ->forDoctor($doctor)
            ->weekday($currentWeekday)
            ->upcoming();

        $restrictedFields = [
            'doctor_id',
            'day_of_week',
            'visit_duration',
            'is_active',
        ];

        $schedule->fill($data);

        $hasRestrictedFieldsChange = collect($restrictedFields)
            ->contains(
                fn(string $field) => $schedule->isDirty($field)
            );

        if (
            $hasRestrictedFieldsChange
            &&
            (clone $appointmentsQuery)->exists()
        ) {
            throw new Exception('The schedule cannot be changed while it has upcoming appointments.');
        }

        if (
            $schedule->isDirty('start_time')
            ||
            $schedule->isDirty('end_time')
        ) {

            $slotDuration = $doctor->visit_duration;
            $this->availabilityService->validateTimeRange(
                $slotDuration,
                $schedule->start_time,
                $schedule->end_time
            );

            $appointmentOutsideNewRange = (clone $appointmentsQuery)
                ->where(function ($query) use ($schedule) {
                    $query
                        ->whereTime('start_datetime', '<', $schedule->start_time)
                        ->orWhereTime('end_datetime', '>', $schedule->end_time);
                })->exists();

            if ($appointmentOutsideNewRange) {
                throw new Exception('The new working hours conflict with upcoming appointments.');
            }
        }

        $schedule->save();
        return $schedule->refresh()->load('doctor');
    }

    public function schedulesByDoctor(Doctor $doctor)
    {
        return DoctorSchedule::query()
            ->forDoctor($doctor)
            ->active()
            ->with(['doctor'])
            ->get();
    }

    public function doctorSelfSchedules(User $user)
    {
        if (!$user->isDoctor()) {
            throw new Exception('you are not a doctor');
        }

        return DoctorSchedule::query()
            ->forDoctor($user->doctor)
            ->active()
            ->with(['doctor'])
            ->get();
    }

    public function destroy(DoctorSchedule $schedule)
    {
        //check for upcoming appointments
        if ($this->appointmentService->hasUpcomingAppointments(
            $schedule->doctor,
            $schedule->day_of_week
        )) {
            throw new Exception('The schedule cannot be deleted while it has upcoming appointments.');
        }

        $schedule->delete();
    }

    public function activate(DoctorSchedule $schedule)
    {
        $schedule->update(['is_active' => true]);
        return $schedule->fresh()->load('doctor');
    }

    public function deactivate(DoctorSchedule $schedule)
    {
        //check for upcoming appointments
        if ($this->appointmentService->hasUpcomingAppointments(
            $schedule->doctor,
            $schedule->day_of_week
        )) {
            throw new Exception('The schedule cannot be deactivated while it has upcoming appointments.');
        }

        $schedule->update([
            'is_active' => false,
        ]);

        return $schedule->fresh()->load('doctor');
    }

    public function index(): Collection
    {
        return DoctorSchedule::with(['doctor'])->get();
    }

    public function show(DoctorSchedule $schedule): DoctorSchedule
    {
        return $schedule->load('doctor');
    }

    public function findLastBookedDate(Doctor $doctor)
    {
        $lastBooked =
            Appointment::forDoctor($doctor)
            ->upcoming()
            ->latest('date')
            ->value('date'); //  it didnt consider start_time 

        return $lastBooked->load('doctor');
    }

    // closeSchedule()
    // calculateEffectiveDate()
    // validateScheduleDates()
}
