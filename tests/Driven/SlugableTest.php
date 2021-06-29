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
use Database\Seeders\ScrappingCommandTestSeeder;
use Database\Seeders\SlugableTestSeeder;
use Tests\TestCase;

class SlugableTest extends TestCase
{
    protected FindSlugable $find;

    protected function setUp(): void
    {
        parent::setUp();
        $this->find = $this->app->make(FindSlugable::class);

        $this->seed(SlugableTestSeeder::class);
    }

    /**
     * @param string $name
     * @param string $surname
     * @testWith ["Tim", "Duncan", 1]
     */
    public function testAuthorSlugsLoading(string $name, string $surname, int $slugCount)
    {
        $author = Author::all()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        $this->assertNotNull($author);
        $this->assertNotEmpty($author->slugs);
        $this->assertSame($slugCount, $author->slugs()->count());
    }

    /**
     * @param string $name
     * @param string $surname
     * @testWith ["Tim", "Duncan"]
     */
    public function testAddNewSlugToAuthor(string $name, string $surname)
    {
        $author = Author::all()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        $this->assertNotNull($author);
        $this->assertNotEmpty($author->slugs);

        $slugAttributes = [
            'slug' => 'test-slug',
            'source' => Source::Starylev
        ];

        $author->slugs()->save(new Slug($slugAttributes));
        $this->assertDatabaseHas(Slug::class, $slugAttributes);
    }

    /**
     * @param string $name
     * @param string $surname
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

        $lastSlug = $author->slugs->last();
        $this->assertTrue($lastSlug->delete());
        $this->assertDatabaseMissing($lastSlug, $lastSlug->toArray());
    }

    /**
     * @param string $slug
     * @testWith ["tim_duncan"]
     */
    public function testFindAuthorBySlug(string $slug)
    {
        $slugAttributes = [
            'slug' => $slug,
            'source' => Source::Bookclub,
        ];

        $author = $this->find->execute(Author::class, [], $slugAttributes);

        $this->assertNotNull($author);
        $this->assertTrue($author instanceof Author);
    }
}
