<?php

namespace App\Services\Actions;

use App\Models\Slug;
use App\Services\Actions\Traits\SlugableTypeResolving;
use App\Services\Conditions\Equal;
use Illuminate\Database\Eloquent\Model;

class CreateSlugable extends Create
{
    use SlugableTypeResolving;

    protected Find $find;

    public function __construct(Find $find)
    {
        $this->find = $find;
    }

    public function execute(Model|string $model, array $params, array $slug = []): ?Model
    {
        $model = $this->getModel($model)
            ->fill($params);

        if (!$model->save()) {
            return null;
        }
        $this->attachSlug($model, $slug);

        return $model;
    }

    protected function findSlug(Model $model, array $params): ?Slug
    {
        if (empty($params['slug']) || empty($params['source'])) {
            throw new \Exception('Not enough parameters to resolve slug.');
        }

        $conditions = [
            new Equal('slugable_type', $this->resolveSlugableType($model)),
            new Equal('slug', $params['slug']),
            new Equal('source', $params['source']),
        ];

        return $this->find->execute(Slug::class, $conditions);
    }

    protected function attachSlug(Model $model, array $params): Slug
    {
        if (empty($params['slug']) || empty($params['source'])) {
            throw new \Exception('Not enough parameters to create slug.');
        }

        $slug = new Slug();
        $slug->fill([
            'slug' => $params['slug'],
            'source' => $params['source']
        ]);

        if (!$model->slugs()->save($slug)) {
            throw new \Exception('Error while saving slug to the database.');
        }
        return $slug;
    }
}
