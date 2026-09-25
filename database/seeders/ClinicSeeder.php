<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinics = [];

        for ($i = 0; $i < 10; $i++) {
            $clinics[] = [
                'name' => fake()->company() . ' Clinic',
                'address' => fake()->address(),
                'phone' => fake()->numerify('09#########'),
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Clinic::upsert(
            $clinics,
            uniqueBy: ['name'],
            update: ['address', 'phone', 'latitude', 'longitude', 'updated_at']
        );
    }
}
