<?php

declare(strict_types = 1);

namespace App\Services\QueryBuilder;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\QueryBuilder\Includes\IncludeInterface;

final class ServiceByUserInclude implements IncludeInterface
{
    public function __construct(
        private readonly int $userId,
        private readonly string $userColumn = 'user_id'
    )
    {
        //
    }

    public function __invoke(Builder $query, string $include)
    {
        $query->with([$include => $this->servicesRelation()]);
    }

    private function servicesRelation(): Closure
    {
        return function(HasMany $query) {
            $query
                ->where($this->userColumn, $this->userId)
                ->orWhereNull($this->userColumn);
        };
    }
}
