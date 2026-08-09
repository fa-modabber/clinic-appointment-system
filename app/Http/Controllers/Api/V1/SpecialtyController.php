<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Specialty\StoreSpecialtyRequest;
use App\Http\Requests\Specialty\UpdateSpecialtyRequest;
use App\Models\Specialty;
use App\Services\SpecialtyService;
use Exception;
use Illuminate\Http\Request;

class SpecialtyController extends ApiController
{
    public function __construct(protected SpecialtyService $specialtyService) {}

    public function store(StoreSpecialtyRequest $request)
    {
        $data = $this->specialtyService->store($request->validated());
        return $this->responseSuccess(
            201,
            'created successfully',
            $data
        );
    }

    public function index()
    {
        $data = $this->specialtyService->index();
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            $data
        );
    }

    public function show(Specialty $specialty)
    {
        $data = $this->specialtyService->show($specialty);
        return $this->responseSuccess(
            $data,
            "retrieved successfully",
            200
        );
    }

    public function update(
        UpdateSpecialtyRequest $request,
        Specialty $specialty
    ) {
        $data = $this->specialtyService->update($specialty, $request->validated());
        return $this->responseSuccess(
            200,
            'Updated successfully',
            $data
        );
    }

    public function destroy(Specialty $specialty)
    {
        if ($specialty->doctors()->exists()) {
            throw new Exception('the specialty has some doctors related');
        }

        $this->specialtyService->destroy($specialty);
        return $this->responseSuccess(
            200,
            "deleted successfully"
        );
    }
    
    public function doctorsBySpecialty(Specialty $specialty)
    {
        $data = $this->specialtyService->doctorsBySpecialty($specialty);
        return $this->responseSuccess(
            $data,
            "retrieved successfully",
            200
        );
    }
}
