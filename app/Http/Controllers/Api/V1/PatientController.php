<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StorePatientRequest;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;


class PatientController extends ApiController
{
    public function __construct(protected PatientService $patientService) {}

    public function store(StorePatientRequest $request)
    {
        $data = $this->patientService->store($request->validated());
        return $this->responseSuccess(
            201,
            'created successfully',
            new PatientResource($data)
        );
    }

    public function index()
    {
        $data = $this->patientService->index();
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            PatientResource::collection($data)
        );
    }

    public function show(Patient $patient)
    {
        Gate::authorize('view', $patient);

        $data = $this->patientService->show($patient);
        return $this->responseSuccess(
            200,
            "retrieved successfully",
            new PatientResource($data)
        );
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $data = $this->patientService->update(
            $patient,
            $request->validated()
        );

        return $this->responseSuccess(
            200,
            'Updated successfully',
            new PatientResource($data)
        );
    }

    public function destroy(Patient $patient)
    {
        $this->patientService->destroy($patient);
        return $this->responseSuccess(
            200,
            "deleted successfully"
        );
    }

    public function doctorSelfPatients(Request $request)
    {
        $data = $this->patientService->doctorSelfPatients($request->user());
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            PatientResource::collection($data)
        );
    }

    public function patientsByDoctor(Doctor $doctor) {
        $data = $this->patientService->patientsByDoctor($doctor);
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            PatientResource::collection($data)
        );
    }
}
