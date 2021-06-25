<?php

namespace App\Services\Actions;

use Illuminate\Database\Eloquent\Model;

class Update extends Find
{
    public function execute(string|Model $model, array $params, array $identifiers = []): ?Model
    {
        $instance = $this->find($model, $identifiers);
        return $instance->update($params) ? $instance : null;
    }

    protected function find(string|Model $model, array $identifiers = []): ?Model
    {
        $instance = parent::execute($model, $identifiers);
        if (empty($instance)) {
            throw new \Exception('Unable to find model from given identifiers.');
        }
        return $instance;
    }
}
