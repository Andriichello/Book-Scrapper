<?php

namespace App\Services\Actions;

use App\Services\Queryable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

class Query extends ModelAction
{
    protected function initQuery(string|Model $model): EloquentBuilder|QueryBuilder
    {
        return $model::query();
    }

    public function extractQueryables(array $params): array
    {
        $queryables = [];
        foreach ($params as $param) {
            if ($param instanceof Queryable) {
                $queryables[] = $param;
            }
        }

        return $queryables;
    }

    public function applyQueryables(EloquentBuilder|QueryBuilder $query, array $queryables): EloquentBuilder|QueryBuilder
    {
        foreach ($queryables as $queryable) {
            $query = $queryable->query($query);
        }
        return $query;
    }

    public function query(string|Model $model, array $params): EloquentBuilder|QueryBuilder
    {
        return $this->applyQueryables($this->initQuery($model), $this->extractQueryables($params));
    }

    public function execute(string|Model $model, array $params): Collection
    {
        return $this->query($model, $params)->get();
    }
}
