<?php

namespace App\Services\Actions;

use Illuminate\Database\Eloquent\Model;

class CreateBook extends CreateSlugable
{
    protected CreateSlugable $createSlugable;

    public function __construct(CreateSlugable $createSlugable)
    {
        $createSlugable;
    }

    public function execute(array $params, array $slug = []): ?Model
    {

    }
}
