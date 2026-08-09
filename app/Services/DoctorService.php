<?php

namespace App\Services;

use App\Models\Doctor;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class DoctorService
{
    public function __construct(
        protected UserService $userService,
        protected AppointmentService $appointmentService
    ) {}

    public function store(array $data): Doctor
    {
        return DB::transaction(function () use ($data) {

            $userData = Arr::only($data, [
                'mobile',
                'first_name',
                'last_name',
                'password',
            ]);

            $user = $this->userService->store($userData);

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'clinic_id' => $data['clinic_id'],
                'medical_code' => $data['medical_code'],
                'bio' => $data['bio'],
                'experience_years' => $data['experience_years'],
                'education' => $data['education'],
                'image' => $data['image'],
                'booking_days_ahead' => $data['booking_days_ahead'],
                'visit_price' => $data['visit_price'],
            ]);

            $user->assignRole('doctor');

            $doctor->specialties()->attach($data['specialties']);

            return $doctor->load(
                'user',
                'specialties',
                'clinic'
            );
        });
    }

    public function index(): Collection
    {
        return Doctor::with(['user', 'specialties', 'clinic'])->get();
    }

    public function show(Doctor $doctor): Doctor
    {
        return $doctor->load(
            'user',
            'specialties',
            'clinic'
        );
    }

    public function update(Doctor $doctor, array $data): Doctor
    {
        return DB::transaction(function () use ($doctor, $data) {

            $userData = Arr::only($data, [
                'mobile',
                'first_name',
                'last_name',
                'password',
            ]);

            if ($userData) {
                $this->userService->update($doctor->user, $userData);
            }

            $doctorData = Arr::only($data, [
                'clinic_id',
                'medical_code',
                'bio',
                'experience_years',
                'education',
                'image',
                'booking_days_ahead',
                'visit_price',
            ]);

            if ($doctorData) {

                $doctor->fill($doctorData);
                if (
                    $doctor->isDirty('visit_duration')
                    &&
                    (
                        $doctor->schedules()->exists()
                        ||
                        $this->appointmentService->hasUpcomingAppointments($doctor))
                ) {
                    throw new Exception('cannot change visit duration while there are upcoming appointments or schedules');
                }

                $doctor->save();
            }

            if (array_key_exists('specialties', $data)) {
                $doctor->specialties()->sync($data['specialties']);
            }

            return $doctor->refresh()->load(
                'user',
                'specialties',
                'clinic'
            );
        });
    }

    public function destroy(Doctor $doctor): void
    {
        if ($this->appointmentService->hasUpcomingAppointments($doctor)) {
            throw new Exception('this doctor has upcoming appointments');
        }

        if ($doctor->schedules()->exists()) {
            throw new Exception('this doctor has some schedules related');
        }

        if ($doctor->scheduleExceptions()->exists()) {
            throw new Exception('this doctor has some schedule exceptions related');
        }

        DB::transaction(function () use ($doctor) {
            $user = $doctor->user;

            $doctor->delete();

            $user->delete();
        });
    }
}
