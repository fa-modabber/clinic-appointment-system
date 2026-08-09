<?php

namespace Database\Seeders;

use App\Models\Specialty;
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
        ];

        foreach ($data as &$item) {
            $item['slug'] = Str::slug($item['title']);
        }

        Specialty::upsert($data, uniqueBy: ['title', 'slug']);
    }
}
