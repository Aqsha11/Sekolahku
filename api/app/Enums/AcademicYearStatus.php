<?php

namespace App\Enums;

enum AcademicYearStatus: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case CLOSED = 'CLOSED';
}