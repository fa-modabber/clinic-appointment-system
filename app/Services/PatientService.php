<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientService
{
    public function __construct(
        protected UserService $userService,
        protected AuthorizationService $authorizationService
    ) {}

    public function store(array $data): Patient
    {
        return DB::transaction(function () use ($data) {

            $userData = Arr::only($data, [
                'mobile',
                'first_name',
                'last_name',
                'password',
            ]);

            $user = $this->userService->store($userData);

            $patient = Patient::create([
                'user_id' => $user->id,
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'marital_status' => $data['marital_status']
            ]);
            $this->authorizationService->assignRole($user, 'patient');

            return $patient->load('user');
        });
    }

    public function index(): Collection
    {
        return Patient::with('user')->get();
    }

    public function show(Patient $patient): Patient
    {
        return $patient->load('user');
    }

    public function update(Patient $patient, array $data): Patient
    {
        return DB::transaction(function () use ($patient, $data) {

            $userData = Arr::only($data, [
                'mobile',
                'first_name',
                'last_name',
                'password',
            ]);

            if ($userData) {
                $this->userService->update($patient->user, $userData);
            }

            $patientData = Arr::only($data, [
                'birth_date',
                'gender',
                'marital_status'
            ]);

            if ($patientData) {
                $patient->update($patientData);
            }

            return $patient->fresh()->load('user');
        });
    }

    public function destroy(Patient $patient): void
    {
        $cancelledCutoff = now()->subDays(30);

        $hasBlockingAppointments = $patient->appointments()
            ->where(function ($query) use ($cancelledCutoff) {
                $query
                    ->whereIn('status', [
                        AppointmentStatus::PENDING->value,
                        AppointmentStatus::CONFIRMED->value,
                        AppointmentStatus::COMPLETED->value,
                    ])
                    ->orWhere(function ($query) use ($cancelledCutoff) {
                        $query
                            ->where('status', AppointmentStatus::CANCELLED->value)
                            ->where('cancelled_at', '>', $cancelledCutoff);
                    });
            })
            ->exists();

        if ($hasBlockingAppointments) {
            throw new Exception('this patient has some appointments');
        }

        DB::transaction(function () use ($patient) {
            $user = $patient->user;

            $patient->delete();
            $user->delete();
        });
    }

    public function doctorSelfPatients(User $user): Collection
    {
        if (!$user->isDoctor()) {
            throw new Exception('You are not a doctor.');
        }

        return Patient::with('user')
            ->whereHas('appointments', function ($query) use ($user) {
                $query
                    ->where('doctor_id', $user->doctor->id)
                    ->whereIn('status', AppointmentStatus::doctorPatientStatuses());
            })
            ->get();
    }

    public function patientsByDoctor(Doctor $doctor): Collection
    {
        return Patient::with('user')
            ->whereHas('appointments', function ($query) use ($doctor) {
                $query
                    ->where('doctor_id', $doctor->id)
                    ->whereIn('status', AppointmentStatus::doctorPatientStatuses());
            })
            ->get();
    }
}
