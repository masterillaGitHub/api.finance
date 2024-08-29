<?php
declare(strict_types=1);

namespace Tests\Generators;

use App\Models\AccountSum;

final class AccountSumGenerator
{
    public static function generate(array $attributes = []): AccountSum
    {
        return AccountSum::factory()->create($attributes);
    }
}
