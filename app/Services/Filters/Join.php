<?php

namespace App\Services\Filters;

use App\Services\Queryable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Join extends Filter
{
    protected string $firstTable;
    protected string $secondTable;
    protected array $conditions;

    /**
     * Join constructor.
     * @param string $firstTable
     * @param string $secondTable
     * @param Queryable[]|Queryable $conditions
     */
    public function __construct(string $firstTable, string $secondTable, array|Queryable $conditions)
    {
        parent::__construct($conditions);
        $this->firstTable = $firstTable;
        $this->secondTable = $secondTable;
    }

    /**
     * @return string
     */
    public function getFirstTable(): string
    {
        return $this->firstTable;
    }

    /**
     * @return string
     */
    public function getSecondTable(): string
    {
        return $this->secondTable;
    }

    public function query(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->join($this->firstTable, function ($q) {
            foreach ($this->conditions as $condition) {
                $q = $condition->query($q);
            }
        });
    }
}
