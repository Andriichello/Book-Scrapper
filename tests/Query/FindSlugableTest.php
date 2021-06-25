<?php

namespace Tests\Query;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Services\Actions\FindSlugable;
use App\Services\Actions\QuerySlugable;
use App\Services\Queryable;
use Database\Seeders\QueryTestSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Tests\TestCase;

class FindSlugableTest extends TestCase
{
    protected FindSlugable $find;

    protected function setUp(): void
    {
        parent::setUp();
        $this->find = new FindSlugable(new QuerySlugable());

        $this->seed(QueryTestSeeder::class);
    }

    protected function find(string $model, string $slug, string $source, array|Queryable $conditions = []): ?Model
    {
        $identifiers = [
            'slug' => $slug,
            'source' => $source,
        ];

        return $this->find->execute($model, Arr::wrap($conditions), $identifiers);
    }

    /**
     * @testWith ["fantastic_books", "bookclub", "Фантастика"]
     * ["missing-slug", "missing-source", null]
     */
    public function testFindGenre(string $slug, string $source, ?string $genreName)
    {
        $instance = $this->find(Genre::class, $slug, $source);

        if (!isset($genreName)) {
            $this->assertNull($instance);
            return;
        }

        $this->assertNotNull($instance);
        $this->assertSame(Genre::class, get_class($instance));
        $this->assertSame($genreName, $instance->name);
    }

    /**
     * @testWith ["frank_herbert", "bookclub", "Френк", "Герберт"]
     * ["missing-slug", "missing-source", null, null]
     */
    public function testFindAuthor(string $slug, string $source, ?string $authorName, ?string $authorSurname)
    {
        $instance = $this->find(Author::class, $slug, $source);

        if (!isset($authorName)) {
            $this->assertNull($instance);
            return;
        }

        $this->assertNotNull($instance);
        $this->assertSame(Author::class, get_class($instance));
        $this->assertSame($authorName, $instance->name);
        $this->assertSame($authorSurname, $instance->surname);
    }

    /**
     * @testWith ["frank_herbert", "bookclub", "Дюна"]
     * ["missing-slug", "missing-source", null]
     */
    public function testFindBook(string $slug, string $source, ?string $title)
    {
        $instance = $this->find(Book::class, $slug, $source);

        if (!isset($authorName)) {
            $this->assertNull($instance);
            return;
        }

        $this->assertNotNull($instance);
        $this->assertSame(Book::class, get_class($instance));
        $this->assertSame($title, $instance->title);
    }
}
