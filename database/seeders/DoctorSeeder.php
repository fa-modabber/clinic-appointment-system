<?php

namespace Database\Seeders;


use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::role('doctor')
            ->get();

        $clinics = Clinic::pluck('id');

        $doctors = [];

        foreach ($users as $user) {
            $doctors[] = [
                'ulid' => Str::ulid(),
                'user_id' => $user->id,
                'clinic_id' => $clinics->random(),
                'medical_code' => fake()->unique()->numerify('MED-#####'),
                'bio' => fake()->optional()->paragraph(),
                'experience_years' => fake()->numberBetween(1, 30),
                'education' => fake()->randomElement([
                    'General Medicine',
                    'Internal Medicine',
                    'Cardiology',
                    'Dermatology',
                    'Neurology',
                ]),
                'image' => null,
                'booking_days_ahead' => fake()->numberBetween(3, 14),
                'visit_price' => fake()->numberBetween(500000, 3000000),
                'visit_duration' => fake()->randomElement([15, 20, 30, 45, 60]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Doctor::upsert(
            $doctors,
            ['user_id'],
            [
                'ulid',
                'clinic_id',
                'medical_code',
                'bio',
                'experience_years',
                'education',
                'image',
                'booking_days_ahead',
                'visit_price',
                'visit_duration',
                'updated_at',
            ]
        );
    }
}
