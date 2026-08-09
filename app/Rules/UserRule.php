<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class UserRule
{

    public static function store(): array
    {
        return [
            'mobile' => [
                'required',
                'string',
                'size:11',
                new IranianMobile,
                Rule::unique('users', 'mobile'),
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],

        ];
    }

    public static function update(User $user): array
    {
        return [
            'mobile' => [
                'required',
                'string',
                'size:11',
                new IranianMobile,
                Rule::unique('users', 'mobile')->ignore($user),
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],

        ];
    }
}
