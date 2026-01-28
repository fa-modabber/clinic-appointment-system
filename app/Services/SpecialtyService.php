<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Specialty;

class SpecialtyService
{
    public function getAll()
    {
        return Specialty::all();
    }

    public function getAllWithDoctorsByCity(int $cityId)
    {
        return Specialty::with(['doctors' => function ($query) use ($cityId) {
            $query->where('city_id', $cityId)
                ->where('is_active', true);
        }])->get();
    }

    public function getWithDoctors($specialty)
    {
        return $specialty->load('doctors');
    }

    public function getAllWithDoctorsCount()
    {
        return Specialty::withCount('doctors')->get();
    }

    public function getDoctorsBySpecialtyAndCity(int $specialtyId, int $cityId)
    {
        return Doctor::whereHas('specialties', function ($q) use ($specialtyId) {
            $q->where('specialty_id', $specialtyId);
        })
            ->where('city_id', $cityId)
            ->get();
    }
}
