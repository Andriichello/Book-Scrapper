<?php

namespace App\Services\Actions;

use App\Services\Filters\Filter;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

class QueryAction extends ModelAction
{
    public function execute(mixed $model, array $params): Collection
    {
        return $this->query($model, $params)->get();
    }

    public function query(mixed $model, array $params): EloquentBuilder|QueryBuilder
    {
        $query = $model::query();
        foreach ($this->filters($params) as $filter) {
            $query = $filter->query($query);
        }

        return $query;
    }

    public function filters(array $params): Collection
    {
        $filters = new Collection();
        foreach ($params as $param) {
            if ($param instanceof Filter) {
                $filters->add($param);
            }
        }

        return $filters;
    }
}
