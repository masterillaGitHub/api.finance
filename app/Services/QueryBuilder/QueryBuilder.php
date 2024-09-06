<?php
declare(strict_types=1);

namespace App\Services\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder as BaseBuilder;

final class QueryBuilder
{
    private Builder $builder;

    private array $defaultSorts = [];
    private array $allowedFields = [];
    private array $allowedIncludes = [];
    private array $allowedFilters = [];
    private array $allowedSorts = [];


    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function setDefaultSorts(array $defaultSorts): self
    {
        $this->defaultSorts = $defaultSorts;

        return $this;
    }

    public function setAllowedFields(array $allowedFields): self
    {
        $this->allowedFields = $allowedFields;

        return $this;
    }

    public function setAllowedIncludes(array $allowedIncludes): self
    {
        $this->allowedIncludes = $allowedIncludes;

        return $this;
    }

    public function setAllowedFilters(array $allowedFilters): self
    {
        $this->allowedFilters = $allowedFilters;

        return $this;
    }

    public function setAllowedSorts(array $allowedSorts): self
    {
        $this->allowedSorts = $allowedSorts;

        return $this;
    }

    public function build(): BaseBuilder
    {
        return BaseBuilder::for($this->builder)
            ->allowedFields($this->allowedFields)
            ->allowedIncludes($this->allowedIncludes)
            ->allowedFilters($this->allowedFilters)
            ->defaultSorts($this->defaultSorts)
            ->allowedSorts($this->allowedSorts);
    }
}
