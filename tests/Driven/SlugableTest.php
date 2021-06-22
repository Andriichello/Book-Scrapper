<?php

namespace Tests\Driven;

use App\Models\Author;
use App\Models\Image;
use App\Models\Slug;
use App\Scrapping\Source;
use Database\Seeders\ImageableTestSeeder;
use Database\Seeders\SlugableTestSeeder;
use Tests\TestCase;

class SlugableTest extends TestCase
{
    /**
     * @param string $name
     * @param string $surname
     * @testWith ["Tim", "Duncan"]
     */
    public function testAuthorSlugsLoading(string $name, string $surname)
    {
        $this->artisan('migrate:fresh');
        $this->seed(SlugableTestSeeder::class);

        $author = Author::all()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        $this->assertNotNull($author);
        $this->assertNotEmpty($author->slugs);
        $this->assertSame(1, $author->slugs()->count());
    }

    /**
     * @param string $name
     * @param string $surname
     * @testWith ["Tim", "Duncan"]
     * @depends testAuthorSlugsLoading
     */
    public function testAddNewSlugToAuthor(string $name, string $surname)
    {
        $author = Author::all()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        $this->assertNotNull($author);
        $this->assertNotEmpty($author->slugs);

        $author->slugs()->save(new Slug([
            'slug' => 'test-slug',
            'source' => Source::Starylev
        ]));
        $author->refresh();

        $this->assertSame(2, $author->slugs()->count());

        print "\ntestAddNewSlugToAuthor()...\n";
        foreach ($author->slugs as $slug) {
            print json_encode($slug, JSON_PRETTY_PRINT) . "\n";
        }
    }

    /**
     * @param string $name
     * @param string $surname
     * @depends testAddNewSlugToAuthor
     * @testWith ["Tim", "Duncan"]
     */
    public function testRemoveSlugFromAuthor(string $name, string $surname)
    {
        $author = Author::all()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        $this->assertNotNull($author);
        $this->assertNotEmpty($author->slugs);

        $author->slugs()
            ->get()
            ->last()
            ->delete();

        $author->refresh();

        $this->assertSame(1, $author->slugs()->count());

        print "\ntestRemoveSlugFromAuthor()...\n";
        foreach ($author->slugs as $slug) {
            print json_encode($slug, JSON_PRETTY_PRINT) . "\n";
        }
    }
}
