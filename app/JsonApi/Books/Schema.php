<?php

namespace App\JsonApi\Books;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'books';

    /**
     * @param \App\Models\Book $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param \App\Models\Book $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'publisher_id' => $resource->publisher_id,
            'code' => $resource->code,
            'title' => $resource->title,
            'original_title' => $resource->original_title,
            'language' => $resource->language,
            'original_language' => $resource->original_language,
            'description' => $resource->description,
            'ebook' => $resource->ebook,
            'price' => $resource->price,
            'club_price' => $resource->club_price,
            'currency' => $resource->currency,
            'cover' => $resource->cover,
            'pages' => $resource->pages,
            'format' => $resource->format,
            'age_restriction' => $resource->age_restriction,
            'rating' => $resource->rating,
            'reviews' => $resource->reviews,
            'isbn' => $resource->isbn,
            'details' => $resource->details,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }

    public function getIncludePaths()
    {
        return ['genres', 'authors', 'publisher'];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'publisher' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => true,
                self::DATA => $resource->publisher,
            ],
            'authors' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => true,
                self::DATA => $resource->authors,
            ],
            'genres' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => true,
                self::DATA => $resource->genres,
            ],
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
