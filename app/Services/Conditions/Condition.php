<?php

namespace App\Services\Conditions;

use App\Services\Queryable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

abstract class Condition implements Queryable
{
    protected string $name;
    protected string $operator;
    protected mixed $value;

    /**
     * Condition constructor.
     * @param string $name
     * @param string $operator
     * @param mixed $value
     */
    public function __construct(string $name, string $operator, mixed $value)
    {
        $this->name = $name;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    public abstract function query(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder;
}
