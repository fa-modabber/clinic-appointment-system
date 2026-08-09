<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->createPermissions();
        $this->assignPermissionToRoles();
    }

    private function createPermissions()
    {
        $permissions = [
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

            // Appointments
            'appointments.view',
            'appointments.create',
            'appointments.update',
            'appointments.delete',
            'appointments.cancel',

            // Schedules
            'schedules.view',
            'schedules.create',
            'schedules.update',
            'schedules.delete',

            // Availabilities
            'availabilities.view'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrcreate([
                'name' => $permission,
                'guard_name' => 'sanctum',
            ]);
        }
    }

    private function assignPermissionToRoles()
    {
        $rolePermissions = [
            'super-admin' => Permission::all(),
            'staff' => [
                'clinics.view',
                'clinics.update',

                'specialties.view',
                'specialties.create',
                'specialties.update',
                'specialties.delete',

                'patients.view',
                'patients.create',
                'patients.update',

                'doctors.view',
                'doctors.create',
                'doctors.update',

                'appointments.view',
                'appointments.create',
                'appointments.update',
                'appointments.cancel',

                'schedules.view',
                'schedules.create',
                'schedules.update',
            ],
            'doctor' => [
                'appointments.view',
                'patients.view',
                'schedules.view',
                'doctors.view',
            ],
            'patient' => [
                'appointments.view',
                'patients.view',
            ]

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
