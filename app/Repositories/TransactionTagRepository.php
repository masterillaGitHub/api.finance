<?php

namespace App\Repositories;

use App\Models\TransactionTag as Model;
use App\Services\QueryBuilder\Filters\FiltersUserId;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TransactionTagRepository extends CoreRepository
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
            ->setDefaultSorts(['order_column', 'id'])
            ->setAllowedIncludes([
            ])
            ->setAllowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::custom('user_id', new FiltersUserId())
            ])
            ->build();

    }
}
