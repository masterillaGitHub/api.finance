<?php

namespace App\Services\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersUserId implements Filter
{

    public function __invoke(Builder $query, $value, string $property = 'user_id'): void
    {
        $userId = $value === 'auth'
            ? auth()->id()
            : (int) $value;

        $query->where($property, $userId);
    }
}
