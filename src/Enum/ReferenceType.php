<?php

namespace App\Enum;
 
enum ReferenceType: string
{
    case TASK = 'Task';
    case TIMELINE = 'Timeline';
    case USER = 'User';
}