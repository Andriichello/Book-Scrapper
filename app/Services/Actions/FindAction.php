<?php

namespace App\Services\Actions;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

class FindAction extends ModelAction
{
    protected QueryAction $queryAction;

    public function __construct(QueryAction $queryAction)
    {
        $this->queryAction = $queryAction;
    }

    public function execute(string $model, array $params): ?Model
    {
        return $this->query($model, $params)->first();
    }

    public function query(string $model, array $params): EloquentBuilder|QueryBuilder
    {
        return $this->queryAction->query($model, $params);
    }
}
