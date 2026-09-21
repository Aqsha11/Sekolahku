<?php

namespace App\Enums;

enum ViolationStatus: string
{
    case REPORTED = 'REPORTED';
    case CONFIRMED = 'CONFIRMED';
    case RESOLVED = 'RESOLVED';
    case CANCELLED = 'CANCELLED';
}