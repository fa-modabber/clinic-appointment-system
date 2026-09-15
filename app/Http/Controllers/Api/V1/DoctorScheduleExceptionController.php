<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\DoctorScheduleException\StoreDoctorScheduleExceptionRequest;
use App\Http\Requests\DoctorScheduleException\UpdateDoctorScheduleExceptionRequest;
use App\Models\DoctorScheduleException;
use App\Services\DoctorScheduleExceptionService;
use Illuminate\Http\Request;

class DoctorScheduleExceptionController extends ApiController
{
    public function __construct(
        protected DoctorScheduleExceptionService $exceptionService
    ) {}

    public function store(StoreDoctorScheduleExceptionRequest $request)
    {
        $data = $this->exceptionService->store(
            $request->validated()
        );
        return $this->responseSuccess(
            201,
            'created successfully',
            $data
        );
    }

    public function index()
    {
        $data = $this->exceptionService->index();
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            $data
        );
    }

    public function update(
        UpdateDoctorScheduleExceptionRequest $request,
        DoctorScheduleException $exception
    ) {
        $data = $this->exceptionService->update(
            $exception,
            $request->validated()
        );
        return $this->responseSuccess(
            200,
            'Updated successfully',
            $data
        );
    }

    public function destroy(
        DoctorScheduleException $exception
    ) {
        $this->exceptionService->destroy($exception);
        return $this->responseSuccess(
            200,
            "deleted successfully"
        );
    }

    public function show(DoctorScheduleException $exception)
    {
        return $this->responseSuccess(
            200,
            "retrieved successfully",
            $exception
        );
    }

    public function activate(
        DoctorScheduleException $exception
    ) {
        $data = $this->exceptionService->activate($exception);
        return $this->responseSuccess(
            200,
            'Updated successfully',
            $data
        );
    }

    public function deactivate(
        DoctorScheduleException $exception
    ) {
        $data = $this->exceptionService->deactivate($exception);
        return $this->responseSuccess(
            200,
            'Updated successfully',
            $data
        );
    }
}
