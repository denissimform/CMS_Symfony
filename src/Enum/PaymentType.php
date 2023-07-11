<?php

namespace App\Enum;

enum PaymentType: string
{
    case FIXED_COST = 'Fixed Cost';
    case HOURLY = 'Hourly';
}