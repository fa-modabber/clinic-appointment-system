<?php

namespace App\Services;

use App\Models\Doctor;

class DoctorService
{
    public function  getByCityAndSpecialty($city, $specialty)
    {
        $doctors = Doctor::where('city_id', $city->id)
            ->whereHas('specialties', function ($q) {
                $q->where('title', 'Neurology');
            })->get();

        return $doctors;
    }
}
