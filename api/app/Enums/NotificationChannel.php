<?php

namespace App\Enums;

enum NotificationChannel: string
{
    case IN_APP = 'IN_APP';
    case EMAIL = 'EMAIL';
    case WHATSAPP = 'WHATSAPP';
    case PUSH = 'PUSH';
}