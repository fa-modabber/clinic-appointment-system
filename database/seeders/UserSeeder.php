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
        $superAdmin = User::create([
            'mobile' => '09123456789',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'password' => Hash::make('1234'),
        ]);

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
