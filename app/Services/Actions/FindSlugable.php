<?php

namespace App\Services\Actions;

use App\Services\Actions\Traits\SlugableTypeResolving;

class FindSlugable extends Find
{
    use SlugableTypeResolving;

    public function __construct(QuerySlugable $queryAction)
    {
        parent::__construct($queryAction);
    }
}
