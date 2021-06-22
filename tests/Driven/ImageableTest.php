<?php

namespace Tests\Driven;

use App\Models\Author;
use App\Models\Image;
use Database\Seeders\ImageableTestSeeder;
use Tests\TestCase;

class ImageableTest extends TestCase
{
    /**
     * @param string $name
     * @param string $surname
     * @testWith ["Tim", "Duncan"]
     */
    public function testAuthorImagesLoading(string $name, string $surname)
    {
        $this->artisan('migrate:fresh');
        $this->seed(ImageableTestSeeder::class);

        $author = Author::all()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        $this->assertNotNull($author);
        $this->assertNotEmpty($author->images);
        $this->assertSame(1, $author->images()->count());
    }

    /**
     * @param string $name
     * @param string $surname
     * @testWith ["Tim", "Duncan"]
     * @depends testAuthorImagesLoading
     */
    public function testAddNewImageToAuthor(string $name, string $surname)
    {
        $author = Author::all()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        $this->assertNotNull($author);
        $this->assertNotEmpty($author->images);

        $author->images()->save(new Image([
            'url' => 'test image url'
        ]));
        $author->refresh();

        $this->assertSame(2, $author->images()->count());

        print "\ntestAddNewImageToAuthor()...\n";
        foreach ($author->images as $image) {
            print json_encode($image, JSON_PRETTY_PRINT) . "\n";
        }
    }

    /**
     * @param string $name
     * @param string $surname
     * @depends testAddNewImageToAuthor
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

        $author->images()
            ->get()
            ->last()
            ->delete();

        $author->refresh();

        $this->assertSame(1, $author->images()->count());

        print "\ntestRemoveImageFromAuthor()...\n";
        foreach ($author->images as $image) {
            print json_encode($image, JSON_PRETTY_PRINT) . "\n";
        }
    }
}
