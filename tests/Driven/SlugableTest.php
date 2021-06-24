<?php

namespace Tests\Driven;

use App\Models\Author;
use App\Models\Slug;
use App\Services\Actions\FindSlugable;
use App\Services\Conditions\Equal;
use App\Services\Filters\Where;
use App\Services\Scrapping\Source;
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

    /**
     * @param string $name
     * @param string $surname
     * @testWith ["Tim", "Duncan"]
     */
    public function testFindSlugableAuthor(string $name, string $surname)
    {
        $this->artisan('migrate:fresh');
        $this->seed(SlugableTestSeeder::class);

        $find = $this->app->make(FindSlugable::class);
        $query = $find->query(Author::class, [
            new Where([
                new Equal('name', $name),
                new Equal('surname', $surname),
                new Equal('biography', $surname),
            ])
        ]);


        print "query: {$query->toSql()}\n";
        $author = $query->first();
        $this->assertNotNull($author);

        print "author: " . json_encode($author, JSON_PRETTY_PRINT) . "\n";
        print "class: " . get_class($author) . "\n";
        print "author: " . json_encode($author->refresh(), JSON_PRETTY_PRINT) . "\n";

        $this->assertSame($name, $author->name);
        $this->assertSame($surname, $author->surname);
    }
}
