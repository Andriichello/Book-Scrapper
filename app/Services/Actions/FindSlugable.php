<?php

namespace App\Services\Actions;

use App\Models\Slug;
use App\Services\Actions\Traits\SlugableTypeResolving;
use App\Services\Conditions\Equal;
use App\Services\Filters\Where;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

class FindSlugable extends Find
{
    use SlugableTypeResolving;

    public function __construct(QuerySlugable $queryAction)
    {
        parent::__construct($queryAction);
    }

    public function execute(Model|string $model, array $params, array $slug = []): ?Model
    {
        return $this->query($model, $params, $slug)->first();
    }

    public function query(Model|string $model, array $params, array $slug = []): EloquentBuilder|QueryBuilder
    {
        if (empty($slug) || empty($slug['slug']) || empty($slug['source'])) {
            throw new \Exception('Unable to find model by given slug data.');
        }

        $params[] = new Where([
            new Equal('slug', $slug['slug']),
            new Equal('source', $slug['source']),
            new Where([
                new Equal('slug', $slug['slug']),
                new Equal('source', $slug['source']),
            ]),
        ]);

        return parent::query($model, $params);
    }


}
