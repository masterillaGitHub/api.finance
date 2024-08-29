<?php
declare(strict_types=1);

namespace Tests\Generators;

use App\Models\Transaction;

final class TransactionGenerator
{
    public static function generate(array $attributes = []): Transaction
    {
        return Transaction::factory()->create($attributes);
    }
}
