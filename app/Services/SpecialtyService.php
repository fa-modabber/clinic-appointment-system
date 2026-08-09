<?php

namespace App\Services;

use App\Models\Specialty;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class SpecialtyService
{
    public function store(array $data): Specialty
    {
        return Specialty::create([
            'title' => $data['title'],
        ]);
    }

    public function index(): Collection
    {
        return Specialty::all();
    }

    public function show(Specialty $specialty): Specialty
    {
        return $specialty;
    }

    public function update(Specialty $specialty, array $data): Specialty
    {
        $specialty->update($data);

        return $specialty->fresh();
    }

    public function destroy(Specialty $specialty): void
    {
        $specialty->delete();
    }

    public function doctorsBySpecialty(Specialty $specialty): Collection
    {
        return $specialty->doctors;
    }
}
