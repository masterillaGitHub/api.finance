<?php

namespace App\Repositories;

use App\Models\Transaction as Model;
use Illuminate\Database\Eloquent\Builder;
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
            ->has('account')
            ->where('user_id', auth()->id())
            ->simplePaginate(40);
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
                'type',
                'account',
                'category',
                'currency',
                'tags',
                'transfer_transaction',
                'transfer_transaction.type',
                'transfer_transaction.account',
                'transfer_transaction.category',
                'transfer_transaction.currency',
                'transfer_transaction.tags',
            ])
            ->setAllowedSorts([
                'id',
                'transaction_at',
            ])
            ->setAllowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('account_id'),
            ])
            ->build();

    }
}
