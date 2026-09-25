<?php

namespace Database\Seeders;

use App\Enums\DoctorScheduleExceptionType;
use App\Models\Doctor;
use App\Models\DoctorScheduleException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorScheduleExceptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // only one exception for each doctor per date
        $doctors = Doctor::all();

        $exceptions = [];

        foreach ($doctors as $doctor) {
            for ($i = 0; $i < 3; $i++) {
                $exceptions[] = [
                    'doctor_id' => $doctor->id,
                    'date' => fake()->dateTimeBetween('now', '+2 months')
                        ->format('Y-m-d'),
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'type' => fake()->randomElement(
                        array_column(
                            DoctorScheduleExceptionType::cases(),
                            'value'
                        )
                    ),
                    'is_active' => fake()->boolean(90),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DoctorScheduleException::upsert(
            $exceptions,
            ['doctor_id', 'date'],
            [
                'start_time',
                'end_time',
                'type',
                'is_active',
                'updated_at',
            ]
        );
    }
}
