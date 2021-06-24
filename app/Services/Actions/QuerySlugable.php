<?php

namespace App\Services\Actions;

use App\Services\Conditions\Equal;
use App\Services\Conditions\On;
use App\Services\Filters\Join;
use App\Services\Filters\Where;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class QuerySlugable extends Query
{
    protected function initQuery(Model|string $model): EloquentBuilder|QueryBuilder
    {
        $model = $this->getModel($model);
        return $model::query()
            ->select($model->getTable() . '.*');
    }

    public function query(string|Model $model, array $params): EloquentBuilder|QueryBuilder
    {
        $model = $this->getModel($model);
        $params[] = new Join('slugables', new On('slugables.slugable_id', $model->getTable() . '.id'));
        $params[] = new Where(new Equal('slugables.slugable_type', $this->slugableType($model)));

        return parent::query($model, $params);
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
