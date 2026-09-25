<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super-admin');

        $staff = User::factory()->create();
        $staff->assignRole('staff');

        for ($i = 0; $i < 10; $i++) {
            $doctor = User::factory()->create();
            $doctor->assignRole('doctor');

            $patient = User::factory()->create();
            $patient->assignRole('patient');
        }
    }
}
