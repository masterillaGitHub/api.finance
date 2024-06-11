<?php
declare(strict_types=1);

namespace App\Services\QueryBuilder\Includes;

use Spatie\QueryBuilder\Includes\IncludeInterface;
use Illuminate\Database\Eloquent\Builder;

final class CountByAuthInclude implements IncludeInterface
{
    public function __construct(
        private readonly string $column = 'user_id'
    )
    {
    }

    public function __invoke(Builder $query, string $include)
    {
        $include = str_replace('Count', '', $include);

        $query->withCount([$include => function ($query) {
            $query->where($this->column, auth()->id());
        }]);
    }
}
