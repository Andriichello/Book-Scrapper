<?php

namespace Tests\Driven;

use App\Models\Author;
use App\Models\Slug;
use App\Services\Actions\Find;
use App\Services\Actions\FindSlugable;
use App\Services\Conditions\Equal;
use App\Services\Filters\OrWhere;
use App\Services\Filters\Where;
use App\Services\Scrapping\Source;
use Database\Seeders\SlugableTestSeeder;
use Tests\TestCase;

class SlugableTest extends TestCase
{
    protected Find $find;

    protected function setUp(): void
    {
        parent::setUp();
        $this->find = $this->app->make(FindSlugable::class);
    }

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

    /**
     * @param string $name
     * @param string $surname
     * @testWith ["tim_duncan"]
     */
    public function testFindAuthorBySlug(string $slug)
    {
        $this->artisan('migrate:fresh');
        $this->seed(SlugableTestSeeder::class);

        $query = $this->find->query(Author::class, [
            new Equal('slug', $slug),
            new Equal('source', Source::Bookclub),
        ]);
        print "query: {$query->toSql()}\n";

        $author = $query->first();
        print "author: " . json_encode($author, JSON_PRETTY_PRINT) . "\n";
        $this->assertNotNull($author);
    }
}
