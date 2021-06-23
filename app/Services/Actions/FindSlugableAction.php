<?php

namespace App\Services\Actions;

use App\Models\Slug;
use App\Services\Conditions\Equal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

class FindSlugableAction
{
    protected FindAction $findAction;

    public function __construct(FindAction $findAction)
    {
        $this->findAction = $findAction;
    }

    public function execute(string $model, array $params): ?Model
    {
        $instance = $this->query($model, $params)->first();
        if ($instance instanceof $model) {
            return $instance;
        }

        throw new \Exception('Found wrong model.');
    }

    public function query(string $model, array $params): MorphTo
    {
        $params['slugable_type'] = new Equal($this->slugableType($model));

        $slug = $this->findAction->execute(Slug::class, $params);
        if (empty($slug)) {
            throw new \Exception('No such slug found.');
        }

        return $slug->slugable();
    }

    protected function slugableType(Model|string $model): string
    {
        $type = array_search($model instanceof Model ? $model::class : $model, Relation::morphMap());
        if (empty($type)) {
            throw new \Exception('No key is mapped to such model.');
        }

        return $type;
    }
}
