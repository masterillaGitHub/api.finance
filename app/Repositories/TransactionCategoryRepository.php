<?php

namespace App\Repositories;

use App\Models\TransactionCategory as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TransactionCategoryRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function get(): Collection
    {
        return $this->query()->get();
    }

    public function userIndex(): Collection
    {
        return $this->query()
            ->where(function (Builder $query) {
                $query->where('user_id', auth()->id())
                    ->orWhereNull('user_id');
            })
            ->whereNull('parent_id')
            ->get();
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
            ->setAllowedIncludes([
                'children',
                'parent'
            ])
            ->setAllowedFilters([
                AllowedFilter::exact('id'),
            ])
            ->build();

    }
}
