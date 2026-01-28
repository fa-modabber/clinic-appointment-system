<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['title' => 'Neurology'],
            ['title' => 'Psychiatry'],
            ['title' => 'Psychology'],
            ['title' => 'Neuroscience'],
            ['title' => 'Sleep Medicine'],
            ['title' => 'Physical Medicine and Rehabilitation'],
            ['title' => 'Physiotherapy'],
            ['title' => 'Urology'],
            ['title' => 'Chiropractic'],
            ['title' => 'Sports Medicine'],
            ['title' => 'Allergy and Immunology'],
            ['title' => 'Geriatrics'],
            ['title' => 'Occupational Medicine'],
            ['title' => 'Pain Management'],
            ['title' => 'Palliative Care'],
            ['title' => 'Preventive Medicine'],
            ['title' => 'Travel Medicine'],
            ['title' => 'General Practitioner (GP)'],
            ['title' => 'Family Medicine'],
            ['title' => 'Internal Medicine'],
        ];

        foreach ($data as &$item) {
            $item['slug'] = Str::slug($item['title']);
        }

        Specialty::upsert($data, uniqueBy: ['title', 'slug']);
    }
}
