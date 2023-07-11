<?php

namespace App\Enum;

enum ProjectStatus: string
{
    case IN_COMMUNICATION = "In Communication";
    case ACCEPTED = "Accepted";
    case REJECTED = "Rejected";
    case INITIALIZED = "Initialized";
    case COMPLETED = "Completed";
}