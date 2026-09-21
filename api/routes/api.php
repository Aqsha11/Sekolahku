<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('health', fn () => ApiResponse::success(['status' => 'ok']));

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1');

        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
            ->middleware('throttle:3,60');

        Route::post('reset-password', [AuthController::class, 'resetPassword'])
            ->middleware('throttle:3,60');

        Route::middleware(['auth:sanctum', 'school.context'])->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
            Route::post('select-school', [AuthController::class, 'selectSchool']);
        });
    });
});