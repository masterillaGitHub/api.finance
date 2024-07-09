<?php

namespace App\Enums;

enum TransactionParentCategory: int
{
    case INITIAL = 1;
    case CORRECTION = 2;
    case EXPENSE = 3;
    case INCOME = 4;
    case TRANSFER = 5;
}
