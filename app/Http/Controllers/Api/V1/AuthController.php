<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RequestOtpRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\PasswordService;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    public function __construct(
        protected AuthService $authService,
        protected PasswordService $passwordService
    ) {}

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        $output =
            [
                'user' => new UserResource($data['user']),
                'token' => $data['token']
            ];

        return $this->responseSuccess(
            200,
            'Logged in successfully.',
            $output
        );
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);
        return $this->responseSuccess(
            200,
            'logged out successfully',
        );
    }

    public function loginRequestOtp(RequestOtpRequest $request)
    {
        $this->authService->loginRequestOtp($request->validated('mobile'));
        return $this->responseSuccess(
            200,
            'otp code sent',
        );
    }

    public function loginverifyOtp(VerifyOtpRequest $request)
    {
        $data = $this->authService->loginverifyOtp($request->validated());

        $output =
            [
                'user' => new UserResource($data['user']),
                'token' => $data['token']
            ];

        return $this->responseSuccess(
            200,
            'Logged in successfully.',
            $output
        );
    }

    // forgot password
    public function resetPasswordRequestOtp(RequestOtpRequest $request)
    {
        $this->passwordService->requestOtp($request->validated('mobile'));
        return $this->responseSuccess(
            200,
            'otp code sent',
        );
    }

    public function resetPasswordVerifyOtp(VerifyOtpRequest $request)
    {
        $data = $this->passwordService->verifyOtp($request->validated());
        return $this->responseSuccess(
            200,
            'you can reset password now ',
            $data
        );
    }

    public function resetPassword(UpdatePasswordRequest $request)
    {
        $data = $this->passwordService->resetPassword($request->validated());
        return $this->responseSuccess(
            200,
            'password reset successfully',
            new UserResource($data)
        );
    }
}
