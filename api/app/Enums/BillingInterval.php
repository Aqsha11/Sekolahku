<?php

namespace App\Enums;

enum BillingInterval: string
{
    case MONTHLY = 'MONTHLY';
    case YEARLY = 'YEARLY';
}