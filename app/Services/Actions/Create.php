<?php

namespace App\Services\Actions;

use Illuminate\Database\Eloquent\Model;

class Create extends ModelAction
{
    public function execute(string|Model $model, array $params): ?Model
    {
        $model = $this->getModel($model);
        $model->fill($params);

        return $model->save() ? $model : null;
    }
}
