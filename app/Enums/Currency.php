<?php
declare(strict_types=1);

namespace App\Enums;

enum Currency: int
{
    case UAH = 1;
    case USD = 2;
    case EUR = 3;
}
