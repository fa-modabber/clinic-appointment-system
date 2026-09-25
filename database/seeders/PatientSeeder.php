<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::role('patient')
            ->get();

        $patients = [];

        foreach ($users as $user) {
            $patients[] = [
                'ulid' => Str::ulid(),
                'user_id' => $user->id,
                'birth_date' => fake()->dateTimeBetween(
                    '-80 years',
                    '-18 years'
                )->format('Y-m-d'),
                'gender' => fake()->randomElement(['female', 'male']),
                'marital_status' => fake()->randomElement([
                    'single',
                    'married',
                    'divorced',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Patient::upsert(
            $patients,
            ['user_id'],
            [
                'ulid',
                'birth_date',
                'gender',
                'marital_status',
                'updated_at',
            ]
        );
    }
}
