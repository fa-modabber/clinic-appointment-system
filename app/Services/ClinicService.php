<?php

namespace App\Services;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Collection;

class ClinicService
{
    public function store(array $data): Clinic
    {
        return Clinic::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);
    }

    public function index(): Collection
    {
        return Clinic::all();
    }

    public function show(Clinic $clinic): Clinic
    {
        return $clinic;
    }

    public function update(Clinic $clinic, array $data): Clinic
    {
        $clinic->update($data);

        return $clinic->fresh();
    }

    public function destroy(Clinic $clinic): void
    {
        $clinic->delete();
    }
}
