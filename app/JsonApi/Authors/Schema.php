<?php

namespace App\JsonApi\Authors;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'authors';

    /**
     * @param \App\Models\Author $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\Models\Author $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'name' => $resource->name,
            'surname' => $resource->surname,
            'biography' => $resource->biography,
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
            'images' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => true,
                self::DATA => $resource->images,
            ],
        ];
    }
}
