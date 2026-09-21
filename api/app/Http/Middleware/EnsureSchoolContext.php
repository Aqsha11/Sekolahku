<?php

namespace App\Http\Middleware;

use App\Enums\ApiErrorCode;
use App\Exceptions\ApiException;
use App\Models\School;
use App\Support\SchoolContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            throw new ApiException(ApiErrorCode::UNAUTHENTICATED, 'Silakan login terlebih dahulu.');
        }

        if ($user->isPlatformUser()) {
            SchoolContext::clear();

            return $next($request);
        }

        $memberships = $user->schools();

        $schoolId = $request->header('X-School-Id');

        $school = $schoolId
            ? $memberships->find($schoolId)
            : $memberships->first();

        if (! $school instanceof School) {
            throw new ApiException(
                ApiErrorCode::INVALID_SCHOOL_CONTEXT,
                'Anda tidak terdaftar di sekolah ini. Pilih sekolah yang valid atau bergabung melalui undangan.',
                [],
                403,
            );
        }

        SchoolContext::set($school);

        return $next($request);
    }
}