<?php

namespace App\Services\Conditions;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Equal extends Condition
{
    public function __construct(string $name, mixed $value)
    {
        parent::__construct($name, '=', $value);
    }

    public function query(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->where($this->name, $this->getOperator(), $this->value);
    }
}
