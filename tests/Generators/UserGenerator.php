<?php
declare(strict_types=1);

namespace Tests\Generators;

use App\Models\User;

final class UserGenerator
{
    public static function generate(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }
}
