<?php

namespace App\Services\Actions;

abstract class Action
{
    public abstract function execute($params): mixed;
}
