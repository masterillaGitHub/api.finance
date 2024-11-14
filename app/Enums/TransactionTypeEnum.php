<?php

namespace App\Enums;

enum TransactionTypeEnum: int
{
    case EXPENSE = 1;
    case INCOME = 2;
    case TRANSFER = 3;

    public function isExpense(): bool
    {
        return $this === self::EXPENSE;
    }

    public function isIncome(): bool
    {
        return $this === self::INCOME;
    }

    public function isTransfer(): bool
    {
        return $this === self::TRANSFER;
    }
}
