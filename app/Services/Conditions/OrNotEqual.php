<?php

namespace App\Services\Conditions;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class OrNotEqual extends Equal
{
    public function __construct(string $name, mixed $value)
    {
        parent::__construct($name, $value);
        $this->operator = '!=';
    }

    public function query(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->orWhere($this->name, $this->getOperator(), $this->value);
    }
}
