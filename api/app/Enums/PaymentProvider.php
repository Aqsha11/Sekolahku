<?php

namespace App\Enums;

enum PaymentProvider: string
{
    case MANUAL = 'MANUAL';
    case MIDTRANS = 'MIDTRANS';
    case XENDIT = 'XENDIT';
    case STRIPE = 'STRIPE';
}