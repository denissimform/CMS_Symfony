<?php

namespace App\Enum;

enum PriorityLevel: string
{
    case LOW = 'Low';
    case MEDIUM = 'Medium';
    case HIGH = 'High';
}