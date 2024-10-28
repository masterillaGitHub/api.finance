<?php
declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

final class MonoBankAccountData extends Data
{
    public function __construct(
        public string $id,
        public ?string $maskedPan,
        public string $currency,
        public int $currencyCode,
        public int $balance,
        public int $creditLimit,
        public string $type,
    )
    {}
}
