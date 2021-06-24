<?php

namespace App\Services\Actions;

abstract class ModelAction
{
    public abstract function execute(string $model, array $params): mixed;
}
