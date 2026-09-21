<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case ACTIVE = 'ACTIVE';
    case COMPLETED = 'COMPLETED';
    case TRANSFERRED = 'TRANSFERRED';
    case DROPPED = 'DROPPED';
}