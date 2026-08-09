<?php

namespace App\Services;

use App\Enums\OtpContext;
use App\Models\OtpCode;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OtpService
{

    public function request(string $mobile, OtpContext $context): void
    {
        $code = $this->generateOtpCode();
        $requestToken = $this->generateRequestToken();

        $this->deletePreviousOtps($mobile, $context);

        OtpCode::create([
            'mobile' => $mobile,
            'context' => $context->value,
            'code_hash' => Hash::make($code),
            'request_token' => $requestToken,
            'expires_at' => now()->addMinutes(1),
        ]);

        $this->send($mobile, $code);
    }

    public function verify(
        string $code,
        OtpCode $otpCode,
    ): void {
        if ($otpCode->isUsed()) {
            throw new Exception('code invalid');
        }

        if ($otpCode->isExpired()) {
            throw new Exception('code expired');
        }

        if (!Hash::check($code, $otpCode->code_hash)) {
            throw ValidationException::withMessages([
                'code' => ['incorrect'],
            ]);
        }

        $otpCode->markAsUsed();
    }

    public function generateOtpCode(): string
    {
        $code = mt_rand(100000, 999999);
        return $code;
    }

    public function generateRequestToken(): string
    {
        do {
            $token = (string) Str::uuid();
        } while (
            OtpCode::where('request_token', $token)->exists()
        );

        return $token;
    }

    public function send(string $mobile, string $code): void
    {
        // send sms
    }



    public function deletePreviousOtps(
        string $mobile,
        OtpContext $context
    ) {
        OtpCode::where('mobile', $mobile)
            ->where('context', $context->value)
            ->delete();
    }

    // maskPhone()

    public function cleanupExpired() {}
}
