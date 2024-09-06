<?php

namespace App\Repositories;

use App\Models\TransactionCategory as Model;
use App\Services\QueryBuilder\Filters\FiltersUserId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
            ->orderByRaw('ISNULL(order_column), order_column ASC')
            ->orderBy('id')
            ->get();
    }

    public function categoryStepIndex(int $typeId): Collection
    {
        return $this->query()
            ->with(['children' => fn(Builder|HasMany $query) => $this->childrenOrderBy($query)])
            ->where(function (Builder $query) {
                $query->where('user_id', auth()->id())
                    ->orWhereNull('user_id');
            })
            ->where(function (Builder $query) use ($typeId) {
                $query->where('type_id', $typeId)
                    ->orWhereNull('type_id');
            })
            ->whereNull('parent_id')
            ->orderByRaw('ISNULL(order_column), order_column ASC')
            ->orderBy('id')
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
                'children.parent',
                'parent',
                'transactions',
            ])
            ->setAllowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('type_id'),
                AllowedFilter::custom('user_id', new FiltersUserId())
            ])
            ->build();
    }

    private function childrenOrderBy(Builder|HasMany $query): void
    {
        $query->orderByRaw('ISNULL(order_column), order_column ASC')
            ->orderBy('id');
    }
}
