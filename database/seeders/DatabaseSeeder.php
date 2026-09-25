<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->call(RolePermissionSeeder::class);
            $this->call(UserSeeder::class);
            $this->call(ClinicSeeder::class);
            $this->call(SpecialtySeeder::class);
            $this->call(DoctorSeeder::class);
            $this->call(DoctorSpecialtySeeder::class);
            $this->call(DoctorScheduleSeeder::class);
            $this->call(DoctorScheduleExceptionSeeder::class);
            $this->call(PatientSeeder::class);
            $this->call(AppointmentSeeder::class);
        });
    }
}
