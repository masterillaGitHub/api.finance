<?php
declare(strict_types=1);

namespace Tests\Generators;

use App\Models\Account;

final class AccountGenerator
{
    public static function generate(array $attributes = []): Account
    {
        return Account::factory()->create($attributes);
    }
}
