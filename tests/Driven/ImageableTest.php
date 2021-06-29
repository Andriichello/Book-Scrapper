<?php

namespace Tests\Driven;

use App\Models\Author;
use App\Models\Image;
use Database\Seeders\ImageableTestSeeder;
use Tests\TestCase;

class ImageableTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ImageableTestSeeder::class);
    }

    /**
     * @param string $name
     * @param string $surname
     * @param int $imagesCount
     * @testWith ["Tim", "Duncan", 2]
     */
    public function testAuthorImagesLoading(string $name, string $surname, int $imagesCount)
    {
        $author = Author::all()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        $this->assertNotNull($author);
        $this->assertNotEmpty($author->images);
        $this->assertSame($imagesCount, $author->images()->count());
    }

    /**
     * @param string $name
     * @param string $surname
     * @testWith ["Tim", "Duncan"]
     */
    public function testAddNewImageToAuthor(string $name, string $surname)
    {
        $author = Author::all()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        $this->assertNotNull($author);
        $this->assertNotEmpty($author->images);

        $imageAttributes = ['url' => 'test-image-url'];
        $author->images()->save(new Image($imageAttributes));
        $this->assertDatabaseHas(Image::class, $imageAttributes);
    }

    /**
     * @param string $name
     * @param string $surname
     * @testWith ["Tim", "Duncan"]
     */
    public function testRemoveImageFromAuthor(string $name, string $surname)
    {
        $author = Author::all()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        $this->assertNotNull($author);
        $this->assertNotEmpty($author->images);

        $lastImage = $author->images->last();
        $this->assertTrue($lastImage->delete());
        $this->assertDatabaseMissing($lastImage, $lastImage->toArray());
    }
}
