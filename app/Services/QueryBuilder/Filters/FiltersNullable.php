<?php

namespace App\Services\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersNullable implements Filter
{

    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value === 'null') {
            $query->whereNull($property);
        }
        else if ($value === 'notNull') {
            $query->whereNotNull($property);
        }
        else {
            $query->where($property, $value);
        }
    }
}
