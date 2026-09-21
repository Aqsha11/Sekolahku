<?php

namespace App\Actions\Auth;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class LogoutAction
{
    public function execute(User $user, ?string $currentTokenId = null, bool $allDevices = false): void
    {
        if ($allDevices) {
            $user->tokens()->delete();

            return;
        }

        if ($currentTokenId) {
            PersonalAccessToken::find($currentTokenId)?->delete();

            return;
        }

        $user->currentAccessToken()?->delete();
    }
}