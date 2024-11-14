<?php

namespace App\Enums;

enum TransactionInputTypeEnum: string
{
    case AUTO = 'auto';
    case MANUAL = 'manual';

    public function isAuto(): bool
    {
        return $this === self::AUTO;
    }

    public function isManual(): bool
    {
        return $this === self::MANUAL;
    }
}
