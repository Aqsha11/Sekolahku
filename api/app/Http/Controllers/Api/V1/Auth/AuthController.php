<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\SelectSchoolAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SelectSchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function login(
        LoginRequest $request,
        LoginAction $action,
    ): JsonResponse {
        $result = $action->execute(
            email: $request->validated('email'),
            password: $request->validated('password'),
            deviceName: $request->validated('device_name', 'auth'),
        );

        return ApiResponse::success([
            'token' => $result['token'],
            'token_type' => $result['token_type'],
            'user' => new UserResource($result['user']->load('schools')),
        ], [
            'message' => 'Login berhasil.',
        ]);
    }

    public function logout(Request $request, LogoutAction $action): JsonResponse
    {
        $action->execute(
            user: $request->user(),
            currentTokenId: $request->user()->currentAccessToken()?->id,
            allDevices: $request->boolean('all_devices'),
        );

        return ApiResponse::success([], ['message' => 'Logout berhasil.']);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user()->load('schools');

        return ApiResponse::success(new UserResource($user));
    }

    public function selectSchool(
        SelectSchoolRequest $request,
        SelectSchoolAction $action,
    ): JsonResponse {
        $school = $action->execute($request->user(), $request->validated('school_id'));

        return ApiResponse::success(
            new SchoolResource($school),
            ['message' => 'Sekolah aktif diperbarui.'],
        );
    }

    public function forgotPassword(
        ForgotPasswordRequest $request,
    ): JsonResponse {
        $status = Password::broker()->sendResetLink(['email' => $request->validated('email')]);

        if ($status !== Password::RESET_LINK_SENT) {
            return ApiResponse::error(
                \App\Enums\ApiErrorCode::VALIDATION_ERROR,
                __('auth.password'),
                [],
                422,
            );
        }

        return ApiResponse::success([], ['message' => 'Link reset password terkirim.']);
    }

    public function resetPassword(
        ResetPasswordRequest $request,
    ): JsonResponse {
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            fn ($user, $password) => $user->forceFill([
                'password' => bcrypt($password),
                'remember_token' => null,
            ])->save(),
        );

        if ($status !== Password::PASSWORD_RESET) {
            return ApiResponse::error(
                \App\Enums\ApiErrorCode::VALIDATION_ERROR,
                __('passwords.token'),
                [],
                422,
            );
        }

        return ApiResponse::success([], ['message' => 'Password berhasil direset.']);
    }
}