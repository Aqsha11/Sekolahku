<?php

namespace App\Actions\Auth;

use App\Enums\ApiErrorCode;
use App\Enums\UserStatus;
use App\Exceptions\ApiException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginAction
{
    public function execute(string $email, string $password, string $deviceName = 'auth'): array
    {
        $user = User::query()
            ->where('email', $email)
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new ApiException(
                ApiErrorCode::UNAUTHENTICATED,
                'Email atau password salah.',
            );
        }

        if ($user->status === UserStatus::SUSPENDED) {
            throw new ApiException(
                ApiErrorCode::FORBIDDEN,
                'Akun Anda sedang diblokir.',
                [],
                403,
            );
        }

        $user->forceFill(['last_login_at' => now()])->save();

        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];
    }
}