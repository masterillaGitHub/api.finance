<?php

namespace App\Repositories;

use App\Services\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Репозиторій для роботи з сутністю
 * Може видавати набори даних
 * Не може створювати/редагувати сутність
 */
abstract class CoreRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    abstract protected function getModelClass(): mixed;

    /**
     * Клонування поточною моделі, щоб модель не зберігала зміни
     */
    protected function startConditions()
    {
        return clone $this->model;
    }

    protected function builder(Builder $builder): QueryBuilder
    {
        return new QueryBuilder($builder);
    }
}
