<?php

namespace App\Services\Conditions;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;

class NotIn extends In
{
    public function __construct(string $name, mixed $value)
    {
        parent::__construct($name, $value);
        $this->operator = 'not in';
    }


    public function query(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->whereNotIn($this->name, Arr::wrap($this->value));
    }
}
