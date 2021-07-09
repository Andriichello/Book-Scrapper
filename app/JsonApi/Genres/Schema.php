<?php

namespace App\JsonApi\Genres;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'genres';

    /**
     * @param \App\Models\Genre $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\Models\Genre $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'name' => $resource->name,
            'description' => $resource->description,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'slugs' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => true,
                self::DATA => $resource->slugs,
            ],
        ];
    }
}
