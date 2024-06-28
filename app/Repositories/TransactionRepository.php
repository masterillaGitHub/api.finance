<?php

namespace App\Repositories;

use App\Models\Transaction as Model;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TransactionRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function get(): Collection
    {
        return $this->query()->get();
    }

    public function userIndex(): \Illuminate\Contracts\Pagination\Paginator
    {
        return $this->query()
            ->where('user_id', auth()->id())
            ->simplePaginate(15);
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
            ->setDefaultSorts(['-created_at'])
            ->setAllowedIncludes([
                'type',
                'account',
                'category',
                'currency',
            ])
            ->setAllowedFilters([
                AllowedFilter::exact('id'),
            ])
            ->build();

    }
}
