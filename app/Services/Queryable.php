<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface Queryable
{
    public function query(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder;
}
