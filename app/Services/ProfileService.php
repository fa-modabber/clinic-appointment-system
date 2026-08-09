<?php

namespace App\Services;

use App\Http\Resources\DoctorResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\UserResource;
use App\Models\User;

class ProfileService
{
    public function show(User $user)
    {
        if ($user->hasRole('doctor')) {
            return DoctorResource::make(
                $user->doctor
            );
        }

        if ($user->hasRole('patient')) {
            return PatientResource::make(
                $user->patient
            );
        }

        return UserResource::make($user);
    }

    public function update(User $user, array $data)
    {
        if ($user->hasRole('doctor')) {
            // return $this->updateDoctor($user, $data);
        }

        if ($user->hasRole('patient')) {
            // return $this->updatePatient($user, $data);
        }

        // return $this->updateStaff($user, $data);
    }

    public function updatePassword(User $user, string $password) {}
}
