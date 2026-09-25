<?php

namespace App\Policies;

use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DoctorSchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DoctorSchedule $doctorSchedule): bool
    {
        if ($user->isStaff()) {
            return true;
        }

        if ($user->isDoctor()) {
            return $doctorSchedule->doctor_id === $user->doctor->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DoctorSchedule $doctorSchedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DoctorSchedule $doctorSchedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DoctorSchedule $doctorSchedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DoctorSchedule $doctorSchedule): bool
    {
        return false;
    }
}
