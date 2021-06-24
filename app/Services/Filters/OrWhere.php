<?php

namespace App\Services\Filters;

use App\Services\Conditions\Condition;
use App\Services\Queryable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class OrWhere extends Filter
{
    protected array $conditions;

    /**
     * Filter constructor.
     * @param Queryable[]|Queryable $conditions
     */
    public function __construct(array|Queryable $conditions)
    {
        parent::__construct($conditions);
    }

    /**
     * @return Condition[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function query(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->orWhere(function ($q) {
            parent::query($q);
        });
    }
}
