<?php

namespace App\Repositories;

use App\Models\AccountCategory as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;

class AccountCategoryRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function mainPage(): Collection
    {
        return $this->query()
            ->whereHas('accounts', function (Builder $query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['accounts' => function (Builder|HasMany $query) {
                $query->with(['sums.currency', 'category'])
                    ->where('user_id', auth()->id());
            }])
            ->get();
    }

    public function transactionPage(): Collection
    {
        return $this->query()
            ->whereHas('accounts', function (Builder $query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['accounts' => function (Builder|HasMany $query) {
                $query->where('user_id', auth()->id());
            }])
            ->get();
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
            ->setAllowedIncludes([
                'accounts',
                'accounts.currency',
                'accounts.sums.currency',
            ])
            ->setAllowedFilters([])
            ->build();

    }
}
