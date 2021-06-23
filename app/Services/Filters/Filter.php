<?php

namespace App\Services\Filters;

use App\Services\Conditions\Condition;
use Illuminate\Database\Eloquent\Builder;

class Filter
{
    protected string $name;
    protected Condition $condition;

    /**
     * Filter constructor.
     * @param string $name
     * @param Condition $condition
     * @param mixed $value
     */
    public function __construct(string $name, Condition $condition)
    {
        $this->name = $name;
        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Condition
     */
    public function getCondition(): Condition
    {
        return $this->condition;
    }

    public function evaluate(mixed $actualValue): bool
    {
        return $this->condition->evaluate($actualValue);
    }

    public function query(Builder $query): Builder
    {
        return $this->condition->query($query, $this->name);
    }
}
