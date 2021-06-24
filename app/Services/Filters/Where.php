<?php

namespace App\Services\Filters;

use App\Services\Queryable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Where extends Filter
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
     * @return Queryable[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function query(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->where(function ($q) {
            parent::query($q);
        });
    }
}
