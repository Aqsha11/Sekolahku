<?php

namespace App\Actions\Auth;

use App\Enums\ApiErrorCode;
use App\Exceptions\ApiException;
use App\Models\School;
use App\Models\User;
use App\Support\SchoolContext;

class SelectSchoolAction
{
    public function execute(User $user, string $schoolId): School
    {
        $school = $user->schools()->find($schoolId);

        if (! $school) {
            throw new ApiException(
                ApiErrorCode::INVALID_SCHOOL_CONTEXT,
                'Anda tidak terdaftar di sekolah yang diminta.',
                [],
                403,
            );
        }

        SchoolContext::set($school);

        return $school;
    }
}