<?php

namespace App\Enums;

enum NotificationDeliveryStatus: string
{
    case PENDING = 'PENDING';
    case SENT = 'SENT';
    case FAILED = 'FAILED';
}