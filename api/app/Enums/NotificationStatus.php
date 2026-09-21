<?php

namespace App\Enums;

enum NotificationStatus: string
{
    case PENDING = 'PENDING';
    case SENT = 'SENT';
    case FAILED = 'FAILED';
    case READ = 'READ';
}