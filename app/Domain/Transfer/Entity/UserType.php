<?php

namespace App\Domain\Transfer\Entity;

enum UserType: string
{
    case PERSONAL = 'personal';
    case BUSINESS = 'business';
}
