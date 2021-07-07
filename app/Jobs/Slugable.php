<?php

namespace App\Jobs;


use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Actions\UpdateSlugable;
use Illuminate\Database\Eloquent\Model;

trait Slugable
{
    protected function findSlugableModel(string $model, FindSlugable $find, string $slug, string $source): ?Model
    {
        try {
            return $find->execute($model, [], [
                'slug' => $slug,
                'source' => $source
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

    protected function updateSlugableModel(string $model, array $data, UpdateSlugable $update, string $slug, string $source)
    {
        try {
            return $update->execute($model, $data, [
                'slug' => $slug,
                'source' => $source
            ]);
        } catch (\Exception $exception) {
            return null;
        }
    }
}

