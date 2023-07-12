<?php

namespace App\Enum;

enum TaskStatus: string
{
    case OPEN = 'Open';
    case IN_PROGRESS = 'In Progress';
    case TO_BE_TESTED = 'To Be Tested';
    case QA_APPROVED = 'QA Approved';
    case ON_HOLD = 'On Hold';
    case READY_TO_DEPLOY = 'Ready To Deploy';
    case COMPLETED = 'Completed';
} 
