<?php

namespace App\Services\Actions\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

trait SlugableTypeResolving
{
    protected function resolveSlugableType(Model|string $model): string
    {
        $type = array_search($model instanceof Model ? $model::class : $model, Relation::morphMap());
        if (empty($type)) {
            throw new \Exception('No key is mapped to such model.');
        }

        return $type;
    }
}
