<?php

namespace App\Services\Actions;

use App\Models\Slug;
use App\Services\Actions\Traits\SlugableTypeResolving;
use App\Services\Conditions\Equal;
use App\Services\Filters\Where;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

class UpdateSlugable extends Update
{
    use SlugableTypeResolving;

    protected FindSlugable $find;

    public function __construct(FindSlugable $find)
    {
        parent::__construct($find->queryAction);
        $this->find = $find;
    }

    public function execute(Model|string $model, array $params, array $slug = []): ?Model
    {
        return parent::execute($model, $params, $slug);
    }

    protected function find(Model|string $model, array $slug = []): ?Model
    {
        return $this->find->execute($model, [], $slug);
    }
}
