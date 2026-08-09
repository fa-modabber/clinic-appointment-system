<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Http\Requests\Appointment\UpdateAppointmentRequest;
use App\Http\Requests\Appointment\UpdateAppointmentStatusRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentAvailabilityService;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends ApiController
{
    public function __construct(
        protected AppointmentService $appointmentService,
    ) {}

    public function store(StoreAppointmentRequest $request)
    {
        $data = $this->appointmentService->store($request->validated());
        return $this->responseSuccess(
            201,
            'created successfully',
            new AppointmentResource($data)
        );
    }

    public function index(Request $request)
    {
        $filters = $request->query();
        $data = $this->appointmentService->index($filters);
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            AppointmentResource::collection($data)
        );
    }

    public function show(Appointment $appointment)
    {
        $data = $this->appointmentService->show($appointment);
        return $this->responseSuccess(
            200,
            "retrieved successfully",
            new AppointmentResource($data)
        );
    }


    public function update(
        UpdateAppointmentRequest $request,
        Appointment $appointment
    ) {
        $data = $this->appointmentService->update(
            $appointment,
            $request->validated()
        );
        return $this->responseSuccess(
            200,
            'Updated successfully',
            new AppointmentResource($data)
        );
    }

    public function destroy(Appointment $appointment)
    {
        $this->appointmentService->destroy($appointment);
        return $this->responseSuccess(
            200,
            "deleted successfully"
        );
    }

    public function cancel(Appointment $appointment)
    {
        $this->appointmentService->cancel($appointment);
        return $this->responseSuccess(
            200,
            "canceled successfully"
        );
    }

    public function doctorSelfAppointments(Request $request)
    {
        $filters = $request->query();
        $user = $request->user();
        $data = $this->appointmentService->doctorSelfAppointments(
            $user,
            $filters
        );
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            AppointmentResource::collection($data)
        );
    }

    public function appointmentsByDoctor(Request $request, Doctor $doctor)
    {
        $filters = $request->query();
        $data = $this->appointmentService->appointmentsByDoctor($doctor, $filters);
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            AppointmentResource::collection($data)
        );
    }

    public function patientSelfAppointments(Request $request)
    {
        $filters = $request->query();
        $user = $request->user();
        $data = $this->appointmentService->patientSelfAppointments(
            $user,
            $filters
        );
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            AppointmentResource::collection($data)
        );
    }

    public function appointmentsByPatient(Request $request, Patient $patient)
    {
        $filters = $request->query();
        $data = $this->appointmentService->appointmentsByPatient(
            $patient,
            $filters
        );
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            AppointmentResource::collection($data)
        );
    }

    public function updateStatus(
        UpdateAppointmentStatusRequest $request,
        Appointment $appointment
    ) {
        $data = $this->appointmentService->updateStatus(
            $appointment,
            $request->validated('status')
        );
        return $this->responseSuccess(
            200,
            "retrieved successfully",
            new AppointmentResource($data)
        );
    }
}
