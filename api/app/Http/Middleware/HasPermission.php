<?php

namespace App\Http\Middleware;

use App\Enums\ApiErrorCode;
use App\Exceptions\ApiException;
use App\Support\SchoolContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            throw new ApiException(ApiErrorCode::UNAUTHENTICATED, 'Silakan login terlebih dahulu.');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $schoolId = SchoolContext::schoolId();

        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission, $schoolId)) {
                return $next($request);
            }
        }

        throw new ApiException(
            ApiErrorCode::FORBIDDEN,
            'Anda tidak memiliki izin untuk melakukan aksi ini.',
            [],
            403,
        );
    }
}