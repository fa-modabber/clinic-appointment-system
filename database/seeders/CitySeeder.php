<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Paris'],
            ['name' => 'Marseille'],
            ['name' => 'Lyon'],
            ['name' => 'Toulouse'],
            ['name' => 'Nice'],
            ['name' => 'Nantes'],
            ['name' => 'Strasbourg'],
            ['name' => 'Montpellier'],
            ['name' => 'Bordeaux'],
            ['name' => 'Lille'],
            ['name' => 'Rennes'],
            ['name' => 'Reims'],
            ['name' => 'Le Havre'],
            ['name' => 'Saint-Étienne'],
            ['name' => 'Toulon'],
            ['name' => 'Grenoble'],
            ['name' => 'Dijon'],
            ['name' => 'Angers'],
            ['name' => 'Nîmes'],
            ['name' => 'Clermont-Ferrand'],
            ['name' => 'Tours'],
            ['name' => 'Amiens'],
            ['name' => 'Annecy'],
            ['name' => 'Avignon'],
            ['name' => 'Metz'],
            ['name' => 'Besançon'],
            ['name' => 'Poitiers'],
            ['name' => 'Orléans'],
            ['name' => 'La Rochelle'],
            ['name' => 'Chambéry'],
        ];

        City::upsert($data, uniqueBy: ["name"]);
    }
}
