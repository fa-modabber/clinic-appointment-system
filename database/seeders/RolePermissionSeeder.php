<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    private array $permissions = [
        // Users
        'users.view',
        'users.create',
        'users.update',
        'users.delete',

        // Authorization
        'authorization.assign-role',
        'authorization.remove-role',
        'authorization.assign-permission',
        'authorization.remove-permission',

        // Clinics
        'clinics.view',
        'clinics.create',
        'clinics.update',
        'clinics.delete',

        // Specialties
        'specialties.view',
        'specialties.create',
        'specialties.update',
        'specialties.delete',

        // Patients
        'patients.view',
        'patients.create',
        'patients.update',
        'patients.delete',

        // Doctors
        'doctors.view',
        'doctors.create',
        'doctors.update',
        'doctors.delete',

        // Availabilities
        'availabilities.view',

        // Schedules
        'schedules.view',
        'schedules.create',
        'schedules.update',
        'schedules.delete',


        // Schedule exception
        'doctor-schedule-exceptions.view',
        'doctor-schedule-exceptions.create',
        'doctor-schedule-exceptions.update',
        'doctor-schedule-exceptions.delete',

        // Appointments
        'appointments.view',
        'appointments.create',
        'appointments.update',
        'appointments.delete',
        'appointments.cancel',
    ];

    private array $staffPermissions = [
        // Clinics
        'clinics.view',
        'clinics.update',

        // Specialties
        'specialties.view',
        'specialties.create',
        'specialties.update',

        // Patients
        'patients.view',
        'patients.create',
        'patients.update',

        // Doctors
        'doctors.view',
        'doctors.create',
        'doctors.update',

        // Availabilities
        'availabilities.view',

        // Schedules
        'schedules.view',
        'schedules.create',
        'schedules.update',

        // Schedule exception
        'doctor-schedule-exceptions.view',
        'doctor-schedule-exceptions.create',
        'doctor-schedule-exceptions.update',

        // Appointments
        'appointments.view',
        'appointments.create',
        'appointments.update',
        'appointments.cancel',
    ];

    private array $doctorPermissions = [
        'appointments.view',
        'patients.view',
        'schedules.view',
        'doctors.view',
    ];

    private array $patientPermissions = [
        'appointments.view',
        'patients.view',
    ];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->createPermissions();
        $this->assignPermissionToRoles();
    }

    private function createPermissions()
    {
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'sanctum',
            ]);
        }
    }

    private function assignPermissionToRoles()
    {
        $rolePermissions = [
            'super-admin' => Permission::pluck('name')->toArray(),
            'staff' => $this->staffPermissions,
            'doctor' => $this->doctorPermissions,
            'patient' => $this->patientPermissions
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'sanctum',
            ]);
            $role->syncPermissions($permissions);
        }
    }
}
