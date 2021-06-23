<?php

namespace App\Services\Conditions;

use Illuminate\Database\Eloquent\Builder;

abstract class Condition
{
    protected string $operator;
    protected mixed $value;

    /**
     * Condition constructor.
     * @param string $operator
     */
    public function __construct(string $operator, mixed $value)
    {
        $this->operator = $operator;
        $this->value = $value;
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

    /**
     * @param mixed $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public abstract function evaluate(mixed $actualValue): bool;

    public abstract function query(Builder $query, string $name): Builder;
}
