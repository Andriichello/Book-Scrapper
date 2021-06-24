<?php

namespace App\Services\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;

class QuerySlugableAction extends QueryAction
{
    public function query(mixed $model, array $params): Builder
    {
        $query = parent::query($model, $params);
    }

    protected function slugableType(Model|string $model): string
    {
        $type = array_search($model instanceof Model ? $model::class : $model, Relation::morphMap());
        if (empty($type)) {
            throw new \Exception('No key is mapped to such model.');
        }

        return $type;
    }
}
