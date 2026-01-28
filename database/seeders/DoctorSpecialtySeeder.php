<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorSpecialty;
use App\Models\Specialty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialtyIds = Specialty::pluck('id')->toArray();

        Doctor::all()->each(
            function ($doctor) use ($specialtyIds) {
                $randomSpecialties = collect($specialtyIds)
                    ->random(rand(1, 3))
                    ->toArray();

                $doctor->specialties()->syncWithoutDetaching($randomSpecialties);
            }

        );
    }
}
