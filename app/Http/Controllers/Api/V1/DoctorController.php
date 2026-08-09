<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Services\DoctorService;
use Illuminate\Support\Facades\Gate;

class DoctorController extends ApiController
{
    public function __construct(protected DoctorService $doctorService) {}

    public function store(StoreDoctorRequest $request)
    {
        $data = $this->doctorService->store($request->validated());
        return $this->responseSuccess(
            201,
            'created successfully',
            new DoctorResource($data)
        );
    }

    public function index()
    {
        Gate::authorize('viewAny');
        
        $data = $this->doctorService->index();
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            DoctorResource::collection($data)
        );
    }

    public function show(Doctor $doctor)
    {
        Gate::authorize('view', $doctor);
        $data = $this->doctorService->show($doctor);
        return $this->responseSuccess(
            200,
            "retrieved successfully",
            new DoctorResource($data)
        );
    }



    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $data = $this->doctorService->update($doctor, $request->validated());
        return $this->responseSuccess(
            200,
            'Updated successfully',
            new DoctorResource($data)
        );
    }
    
    public function destroy(Doctor $doctor)
    {
        $this->doctorService->destroy($doctor);
        return $this->responseSuccess(
            200,
            "deleted successfully"
        );
    }
}
