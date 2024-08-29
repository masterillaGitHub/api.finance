<?php
declare(strict_types=1);

namespace Tests\Generators;

use App\Models\TransactionCategory;

final class TransactionCategoryGenerator
{
    public static function generate(array $attributes = []): TransactionCategory
    {
        return TransactionCategory::factory()->create($attributes);
    }
}
