<?php

namespace App\Enums;

enum AccountPlaceTypeEnum: string
{
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';

    public function isInternal(): bool
    {
        return $this === self::INTERNAL;
    }

    public function isExternal(): bool
    {
        return $this === self::EXTERNAL;
    }
}
