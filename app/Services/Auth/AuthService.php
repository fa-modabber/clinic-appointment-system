<?php

namespace App\Services;

use App\Enums\OtpContext;
use App\Models\OtpCode;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(protected OtpService $otpService) {}

    public function getValidUser(string $mobile): User
    {
        $user = User::where('mobile', $mobile)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'mobile' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->isActive()) {
            throw ValidationException::withMessages([
                'mobile' => ['Your account is inactive.'],
            ]);
        }

        return $user;
    }

    public function login(array $data): array
    {
        $mobile = $data['mobile'];
        $password = $data['password'];

        $user = $this->getValidUser($mobile);

        if (!$user->canLoginWithPassword()) {
            throw new Exception('you cannot login');
        }

        if (!Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['incorrect password'],
            ]);
        }

        $token = $user->createToken('api')->plainTextToken;
        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function logout(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }



    public function loginRequestOtp(string $mobile)
    {
        $user = $this->getValidUser($mobile);

        if (! $user->canLoginWithOtp()) {
            throw ValidationException::withMessages([
                'mobile' => ['OTP login is not available for this account.'],
            ]);
        }

        $this->otpService->request($mobile, OtpContext::LOGIN);
    }


    public function loginverifyOtp(array $data)
    {
        $otpCode = OtpCode::where('request_token', $data['request_token'])
            ->where('context', OtpContext::LOGIN->value)
            ->firstOrFail();

        $user = $this->getValidUser($otpCode->mobile);

        if (! $user->canLoginWithOtp()) {
            throw ValidationException::withMessages([
                'mobile' => ['OTP login is not available for this account.'],
            ]);
        }
        $this->otpService->verify(
            $data['code'],
            $otpCode
        );

        $token = $user->createToken('api')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
