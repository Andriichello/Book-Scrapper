<?php

namespace App\Services\Filters;

use App\Services\Queryable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Join extends Filter
{
    protected string $table;
    protected array $conditions;

    /**
     * Join constructor.
     * @param string $table
     * @param Queryable[]|Queryable $conditions
     */
    public function __construct(string $table, array|Queryable $conditions)
    {
        parent::__construct($conditions);
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    public function query(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->join($this->table, function ($q) {
            parent::query($q);
        });
    }
}
