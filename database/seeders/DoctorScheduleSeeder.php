<?php

namespace Database\Seeders;

use App\Enums\Week;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        // this inserts only one schedule per weekday
        $doctors = Doctor::all();

        $schedules = [];

        foreach ($doctors as $doctor) {
            foreach (Week::cases() as $day) {
                if (fake()->boolean(70)) {
                    $schedules[] = [
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $day->value,
                        'start_time' => '09:00:00',
                        'end_time' => '17:00:00',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DoctorSchedule::upsert(
            $schedules,
            ['doctor_id', 'day_of_week'],
            [
                'start_time',
                'end_time',
                'is_active',
                'updated_at',
            ]
        );
    }
}
