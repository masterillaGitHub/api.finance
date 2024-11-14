<?php
declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

final class MonoBankStatementData extends Data
{
    public function __construct(
        public string $id,
        public int $time,
        public string $description,
        public int $mcc,
        public int $originalMcc,
        public int $amount,
        public bool $hold,
        public int $operationAmount,
        public int $currencyCode,
        public int $commissionRate,
        public int $cashbackAmount,
        public int $balance,
        public ?string $receiptId,
        public ?string $comment,
        public ?string $invoiceId,
        public ?string $counterEdrpou,
        public ?string $counterIban,
        public ?string $counterName
    )
    {}
}
