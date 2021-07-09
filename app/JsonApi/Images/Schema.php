<?php

namespace App\JsonApi\Images;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'images';

    /**
     * @param \App\Models\Image $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\Models\Image $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'url' => $resource->url,
            'imageable_id' => $resource->imageable_id,
            'imageable_type' => $resource->imageable_type,
        ];
    }
}
