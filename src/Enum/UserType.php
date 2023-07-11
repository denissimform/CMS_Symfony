<?php

namespace App\Enum;

enum UserType: string
{
    case USER = 'User';
    case COMPANY = 'Company';
    case CLIENT = 'Client';
}