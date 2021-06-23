<?php

namespace App\Services\Actions;

use Illuminate\Database\Eloquent\Model;

abstract class ModelAction
{
    public abstract function execute(string $model, array $params): mixed;
}
