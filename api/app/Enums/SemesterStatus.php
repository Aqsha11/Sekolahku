<?php

namespace App\Enums;

enum SemesterStatus: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case CLOSED = 'CLOSED';
}