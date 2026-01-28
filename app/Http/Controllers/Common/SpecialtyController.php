<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Services\SpecialtyService;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function __construct(protected SpecialtyService $specialtyService) {}

    public function getAll()
    {
        $specielties = $this->specialtyService->getAll();
        return $specielties;
    }

    public function getByIdWithDoctors(Specialty $specialty)
    {
        $specialty = $this->specialtyService->getWithDoctors($specialty);
        return $specialty;
      
    }

    public function getAllWithDoctors() {
        $specielties = $this->specialtyService->getAllWithDoctors();
        return $specielties;
    }
}
