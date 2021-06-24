<?php

namespace App\Services\Actions;

use App\Services\Actions\Traits\SlugableTypeResolving;
use App\Services\Conditions\Equal;
use App\Services\Conditions\On;
use App\Services\Filters\Join;
use App\Services\Filters\Where;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

class QuerySlugable extends Query
{
    use SlugableTypeResolving;

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
        $params[] = new Where(new Equal('slugables.slugable_type', $this->resolveSlugableType($model)));

        return parent::query($model, $params);
    }


}
