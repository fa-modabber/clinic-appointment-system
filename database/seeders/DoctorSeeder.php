<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 4; $i++) {
            Doctor::upsert([
                "name" => "Dr." . fake()->name(),
                "is_active" => fake()->boolean(90),
                "city_id" => City::inRandomOrder()->first()->id,
                "image" => 'doctor-icon.png'
            ], uniqueBy: [], update: ["name", "is_active", "city_id", "image"]);
        }
    }
}
