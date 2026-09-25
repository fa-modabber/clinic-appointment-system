<?php

namespace Database\Seeders;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $patients = Patient::pluck('id');
        $doctors = Doctor::pluck('id');

        $appointments = [];

        for ($i = 0; $i < 50; $i++) {
            $appointments[] = [
                'ulid' => Str::ulid(),
                'patient_id' => $patients->random(),
                'doctor_id' => $doctors->random(),
                'date' => fake()->dateTimeBetween('now', '+2 months')
                    ->format('Y-m-d'),
                'start_time' => fake()->randomElement([
                    '09:00:00',
                    '09:30:00',
                    '10:00:00',
                    '10:30:00',
                    '11:00:00',
                    '14:00:00',
                    '14:30:00',
                    '15:00:00',
                    '15:30:00',
                    '16:00:00',
                ]),
                'status' => fake()->randomElement(
                    array_column(AppointmentStatus::cases(), 'value')
                ),
                'cancelled_at' => null,
                'type' => fake()->randomElement([
                    'online',
                    'in-site',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Appointment::upsert(
            $appointments,
            ['ulid'],
            [
                'patient_id',
                'doctor_id',
                'date',
                'start_time',
                'status',
                'cancelled_at',
                'type',
                'updated_at',
            ]
        );
    }
}
