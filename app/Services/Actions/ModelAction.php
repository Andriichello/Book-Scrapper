<?php

namespace App\Services\Actions;

use Illuminate\Database\Eloquent\Model;

abstract class ModelAction
{
    public abstract function execute(string|Model $model, array $params): mixed;

    protected function getModel(string|Model $model): Model
    {
        if ($model instanceof Model) {
            return $model;
        }

        return new $model();
    }
}
