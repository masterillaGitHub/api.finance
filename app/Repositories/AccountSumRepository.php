<?php

namespace App\Repositories;

use App\Models\AccountSum as Model;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;

class AccountSumRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function get(): Collection
    {
        return $this->query()->get();
    }

    public function whereId(int $id): ?Model
    {
        /** @var Model|null $res */
        $res = $this->query()
            ->where('id', $id)
            ->first();

        return $res;
    }

    public function query(): QueryBuilder
    {
        /** @var Model $model */
        $model = $this->startConditions();

        return $this->builder($model::query())
            ->setAllowedIncludes(['currency'])
            ->setAllowedFilters([])
            ->build();

    }
}
