<?php

namespace App\Services\Actions;

use App\Services\Conditions\Condition;
use App\Services\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class QueryAction extends ModelAction
{
    public function execute(mixed $model, array $params): Collection
    {
        return $this->query($model, $params)->get();
    }

    public function query(mixed $model, array $params): Builder
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

        foreach ($params as $key => $value) {
            if (!($value instanceof Condition)) {
                continue;
            }

            $filters->add(new Filter($key, $value));
        }

        return $filters;
    }
}
