<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Clinic\StoreClinicRequest;
use App\Http\Requests\Clinic\UpdateClinicRequest;
use App\Http\Resources\ClinicResource;
use App\Models\Clinic;
use App\Services\ClinicService;

class ClinicController extends ApiController
{

    public function __construct(protected ClinicService $clinicService) {}

    public function store(StoreClinicRequest $request)
    {
        $data = $this->clinicService->store($request->validated());
        return $this->responseSuccess(
            201,
            'created successfully',
            new ClinicResource($data)
        );
    }

    public function index()
    {
        $data = $this->clinicService->index();
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            ClinicResource::collection($data)
        );
    }

    public function show(Clinic $clinic)
    {
        $data = $this->clinicService->show($clinic);
        return $this->responseSuccess(
            200,
            "retrieved successfully",
            new ClinicResource($data)
        );
    }

    public function update(UpdateClinicRequest $request, Clinic $clinic)
    {
        $data = $this->clinicService->update($clinic, $request->validated());
        return $this->responseSuccess(
            200,
            'Updated successfully',
            new ClinicResource($data)
        );
    }

    public function destroy(Clinic $clinic)
    {
        $this->clinicService->destroy($clinic);
        return $this->responseSuccess(
            200,
            "deleted successfully"
        );
    }
}
