<?php

namespace App\Services\Conditions;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\JoinClause;

class On extends Equal
{
    public function query(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        if ($query instanceof JoinClause) {
            return $query->on($this->name, $this->getOperator(), $this->value);
        }

        return parent::query($query);
    }
}
