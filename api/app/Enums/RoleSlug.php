<?php

namespace App\Enums;

enum RoleSlug: string
{
    case SUPER_ADMIN = 'super_admin';
    case PLATFORM_ADMIN = 'platform_admin';
    case ORGANIZATION_ADMIN = 'organization_admin';
    case SCHOOL_ADMIN = 'school_admin';
    case HEADMASTER = 'headmaster';
    case TEACHER = 'teacher';
    case HOMEROOM_TEACHER = 'homeroom_teacher';
    case COUNSELOR = 'counselor';
    case STAFF = 'staff';
    case STUDENT = 'student';
    case PARENT = 'parent';
}