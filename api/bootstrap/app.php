<?php

use App\Enums\ApiErrorCode;
use App\Support\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Routing\Exceptions\ValidationException as RoutingValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'school.context' => \App\Http\Middleware\EnsureSchoolContext::class,
            'permission' => \App\Http\Middleware\HasPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function ($request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::error(
                ApiErrorCode::VALIDATION_ERROR,
                'Data tidak valid.',
                $e->errors(),
            );
        });

        $exceptions->render(function (RoutingValidationException $e) {
            return ApiResponse::error(
                ApiErrorCode::VALIDATION_ERROR,
                'Data tidak valid.',
                $e->errors(),
            );
        });

        $exceptions->render(function (AuthenticationException $e) {
            return ApiResponse::error(ApiErrorCode::UNAUTHENTICATED, 'Silakan login terlebih dahulu.');
        });

        $exceptions->render(function (AuthorizationException $e) {
            return ApiResponse::error(ApiErrorCode::FORBIDDEN, 'Anda tidak memiliki akses untuk tindakan ini.');
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            return ApiResponse::error(ApiErrorCode::NOT_FOUND, 'Data tidak ditemukan.');
        });

        $exceptions->render(function (NotFoundHttpException $e) {
            return ApiResponse::error(ApiErrorCode::NOT_FOUND, 'Endpoint tidak ditemukan.');
        });

        $exceptions->render(function (ThrottleRequestsException $e) {
            return ApiResponse::error(ApiErrorCode::RATE_LIMITED, 'Terlalu banyak permintaan. Silakan coba lagi.', [
                'retry_after' => $e->getHeaders()['Retry-After'] ?? null,
            ]);
        });

        $exceptions->render(function (HttpExceptionInterface $e) {
            return ApiResponse::error(
                ApiErrorCode::SERVER_ERROR,
                $e->getMessage() ?: 'Terjadi kesalahan pada server.',
                [],
                $e->getStatusCode(),
            );
        });

        $exceptions->render(function (Throwable $e) {
            if (config('app.debug')) {
                return null;
            }

            $errorId = 'ERR-'.now()->format('Ymd-His');
            Log::error($errorId, ['exception' => $e]);

            return ApiResponse::error(ApiErrorCode::SERVER_ERROR, 'Terjadi kesalahan pada server.', [
                'error_id' => $errorId,
            ]);
        });
    })->create();