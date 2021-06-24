<?php

namespace App\Services\Actions;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Find extends ModelAction
{
    protected Query $queryAction;

    public function __construct(Query $queryAction)
    {
        $this->queryAction = $queryAction;
    }

    public function execute(string|Model $model, array $params): ?Model
    {
        return $this->query($model, $params)->first();
    }

    public function query(string|Model $model, array $params): EloquentBuilder|QueryBuilder
    {
        return $this->queryAction->query($model, $params);
    }
}
