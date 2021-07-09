<?php

namespace App\JsonApi\Slugs;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'slugs';

    /**
     * @param \App\Models\Slug $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\Models\Slug $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'slug' => $resource->slug,
            'source' => $resource->source,
            'slugable_id' => $resource->slugable_id,
            'slugable_type' => $resource->slugable_type,
        ];
    }
}
