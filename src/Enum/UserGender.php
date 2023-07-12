<?php

namespace App\Enum;

enum UserGender: string
{
    case MALE = "Male";
    case FEMALE = "Female";
    case OTHER = "Other";
}