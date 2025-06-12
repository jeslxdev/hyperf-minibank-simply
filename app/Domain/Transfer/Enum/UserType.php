<?php

namespace App\Domain\Transfer\Enum;

enum UserType: string
{
    case PERSONAL = 'personal';
    case BUSINESS = 'business';
}
