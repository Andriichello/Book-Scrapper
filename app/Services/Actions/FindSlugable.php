<?php

namespace App\Services\Actions;

use App\Services\Conditions\Equal;
use App\Services\Conditions\On;
use App\Services\Filters\Join;
use App\Services\Filters\Where;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;

class FindSlugable extends Find
{
    public function __construct(QuerySlugable $queryAction)
    {
        parent::__construct($queryAction);
    }
}
