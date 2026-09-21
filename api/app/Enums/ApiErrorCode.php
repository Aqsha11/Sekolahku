<?php

namespace App\Enums;

enum ApiErrorCode: string
{
    case UNAUTHENTICATED = 'UNAUTHENTICATED';
    case UNAUTHORIZED = 'UNAUTHORIZED';
    case FORBIDDEN = 'FORBIDDEN';
    case VALIDATION_ERROR = 'VALIDATION_ERROR';
    case NOT_FOUND = 'NOT_FOUND';
    case CONFLICT = 'CONFLICT';
    case DUPLICATE_RESOURCE = 'DUPLICATE_RESOURCE';
    case TENANT_ACCESS_DENIED = 'TENANT_ACCESS_DENIED';
    case INVALID_SCHOOL_CONTEXT = 'INVALID_SCHOOL_CONTEXT';
    case ATTENDANCE_ALREADY_EXISTS = 'ATTENDANCE_ALREADY_EXISTS';
    case INVALID_ATTENDANCE_CORRECTION = 'INVALID_ATTENDANCE_CORRECTION';
    case SCHEDULE_CONFLICT = 'SCHEDULE_CONFLICT';
    case FILE_ACCESS_DENIED = 'FILE_ACCESS_DENIED';
    case RATE_LIMITED = 'RATE_LIMITED';
    case SERVER_ERROR = 'SERVER_ERROR';

    public function statusCode(): int
    {
        return match ($this) {
            self::UNAUTHENTICATED => 401,
            self::UNAUTHORIZED,
            self::FORBIDDEN,
            self::TENANT_ACCESS_DENIED,
            self::INVALID_SCHOOL_CONTEXT,
            self::FILE_ACCESS_DENIED => 403,
            self::NOT_FOUND => 404,
            self::CONFLICT,
            self::DUPLICATE_RESOURCE,
            self::ATTENDANCE_ALREADY_EXISTS,
            self::INVALID_ATTENDANCE_CORRECTION,
            self::SCHEDULE_CONFLICT => 409,
            self::VALIDATION_ERROR => 422,
            self::RATE_LIMITED => 429,
            self::SERVER_ERROR => 500,
        };
    }
}