<?php

namespace App\Services\Conditions;

use Illuminate\Database\Eloquent\Builder;

class Equal extends Condition
{
    public function __construct(mixed $value)
    {
        parent::__construct('=', $value);
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    public function evaluate(mixed $actualValue): bool
    {
        return $actualValue == $this->value;
    }

    public function query(Builder $query, string $name): Builder
    {
        return $query->where($name, $this->getOperator(), $this->value);
    }
}
