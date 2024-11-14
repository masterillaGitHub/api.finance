<?php

namespace App\Enums;

enum AccountCategoryIdEnum: int
{
    case CASH = 1;
    case BANK_ACCOUNT = 2;
    case DEBT = 3;
    case ANOTHER_ASSET = 4;
}
