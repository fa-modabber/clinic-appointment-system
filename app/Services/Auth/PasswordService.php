<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Enums\OtpContext;
use App\Models\OtpCode;
use Exception;
use Illuminate\Support\Facades\DB;

class PasswordService
{
    public function __construct(protected OtpService $otpService) {}

    public function getValidPasswordUser(string $mobile): User
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

        if (! $user->canResetPassword()) {
            throw ValidationException::withMessages([
                'mobile' => ['reset password is not available for this account.'],
            ]);
        }

        return $user;
    }

    public function requestOtp(string $mobile): void
    {
        $user = $this->getValidPasswordUser($mobile);
        $this->otpService->request($user->mobile, OtpContext::PASSWORD_RESET);
    }

    public function verifyOtp(array $data): array
    {
        $otpCode = OtpCode::where('request_token', $data['request_token'])
            ->where('context', OtpContext::PASSWORD_RESET->value)
            ->firstOrFail();

        $user = $this->getValidPasswordUser($otpCode->mobile);

        $this->otpService->verify(
            $data['code'],
            $otpCode
        );

        $resetToken = str()->random(64);

        DB::table('password_reset_tokens')
            ->where('user_id', $user->id)
            ->delete();

        DB::table('password_reset_tokens')->insert([
            'user_id' => $user->id,
            'token' => $resetToken,
            'expires_at' => now()->addMinutes(5)
        ]);

        return [
            'reset_token' => $resetToken,
        ];
    }

    public function resetPassword(array $data): User
    {
        $resetPasswordRecord = DB::table('password_reset_tokens')
            ->where('token', $data['token'])->first();

        if (!$resetPasswordRecord) {
            throw new Exception('reset not possible');
        }

        if ($resetPasswordRecord->expires_at < now()) {
            throw new Exception('code expired');
        }

        $user = User::findOrFail($resetPasswordRecord->user_id);
        $this->getValidPasswordUser($user->mobile);

        DB::transaction(function () use ($user, $data) {
            $user->update([
                'password' => $data['password']
            ]);

            DB::table('password_reset_tokens')->where([
                'token' => $data['token']
            ])->delete();

            $user->tokens()->delete();
        });

        return $user;
    }
}
