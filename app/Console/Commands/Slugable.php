<?php

namespace App\Console\Commands;


use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Conditions\Equal;
use Illuminate\Database\Eloquent\Model;

trait Slugable
{
    protected function findSlugableModel(string $model, FindSlugable $find, string $slug, string $source): ?Model
    {
        try {
            return $find->execute($model, [
                new Equal('slug', $slug),
                new Equal('source', $source)
            ]);
        } catch (\Exception $exception) {
            return null;
        }
    }

    protected function createSlugableModel(string $model, array $data, CreateSlugable $create, string $slug, string $source): ?Model
    {
        try {
            return $create->execute($model, $data, [
                'slug' => $slug,
                'source' => $source
            ]);
        } catch (\Exception $exception) {
            return null;
        }
    }
}

