<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Schedule\DoctorStoreScheduleRequest;
use App\Http\Requests\Schedule\DoctorUpdateScheduleRequest;
use App\Http\Resources\DoctorScheduleResource;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Services\AvailabilityService;
use App\Services\DoctorScheduleService;
use App\Services\SlotService;
use Illuminate\Http\Request;


class ScheduleController extends ApiController
{
    public function __construct(
        protected DoctorScheduleService $scheduleService,
        protected AvailabilityService $availabilityService
    ) {}

    public function store(DoctorStoreScheduleRequest $request)
    {
        $data = $this->scheduleService->store($request->validated());
        return $this->responseSuccess(
            201,
            'created successfully',
            new DoctorScheduleResource($data)
        );
    }

    public function index()
    {
        $data = $this->scheduleService->index();
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            DoctorScheduleResource::collection($data)
        );
    }

    public function show(DoctorSchedule $schedule)
    {
        $data = $this->scheduleService->show($schedule);
        return $this->responseSuccess(
            200,
            "retrieved successfully",
            new DoctorScheduleResource($data)
        );
    }

    public function update(
        DoctorUpdateScheduleRequest $request,
        DoctorSchedule $schedule
    ) {
        $data = $this->scheduleService->update(
            $schedule,
            $request->validated()
        );
        return $this->responseSuccess(
            200,
            'Updated successfully',
            new DoctorScheduleResource($data)
        );
    }

    public function destroy(DoctorSchedule $schedule)
    {
        $this->scheduleService->destroy($schedule);
        return $this->responseSuccess(
            200,
            "deleted successfully"
        );
    }

    public function schedulesByDoctor(Doctor $doctor)
    {
        $data = $this->scheduleService->schedulesByDoctor($doctor);
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            DoctorScheduleResource::collection($data)
        );
    }

    public function doctorSelfSchedules(Request $request)
    {
        $user = $request->user();
        $data = $this->scheduleService->doctorSelfSchedules($user);
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            DoctorScheduleResource::collection($data)
        );
    }

    public function activate(DoctorSchedule $schedule)
    {
        $data = $this->scheduleService->activate($schedule);
        return $this->responseSuccess(
            200,
            'Updated successfully',
            new DoctorScheduleResource($data)
        );
    }

    public function deactivate(DoctorSchedule $schedule)
    {
        $data = $this->scheduleService->deactivate($schedule);
        return $this->responseSuccess(
            200,
            'Updated successfully',
            new DoctorScheduleResource($data)
        );
    }

}
