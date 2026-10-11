<?php

namespace App\Services;

use App\Models\Specialty;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Exception;

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
        if ($specialty->doctors()->exists()) {
            throw new Exception(
                'This specialty has associated doctors.'
            );
        }
        $specialty->delete();
    }

    public function doctorsBySpecialty(Specialty $specialty): Collection
    {
        return $specialty->doctors;
    }
}
