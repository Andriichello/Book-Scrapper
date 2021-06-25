<?php

namespace Tests\Query;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Services\Actions\QuerySlugable;
use App\Services\Conditions\Equal;
use App\Services\Filters\Where;
use App\Services\Queryable;
use Database\Seeders\QueryTestSeeder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Tests\TestCase;

class QuerySlugableTest extends TestCase
{
    use DatabaseMigrations;

    protected QuerySlugable $query;

    protected function setUp(): void
    {
        parent::setUp();
        $this->query = new QuerySlugable();

        $this->seed(QueryTestSeeder::class);
    }

    protected function query(string $model, array|Queryable $conditions = []): EloquentBuilder|QueryBuilder
    {
        return $this->query->query($model, Arr::wrap($conditions));
    }

    protected function print(EloquentBuilder|QueryBuilder $query): void
    {
        print "query: {$query->toSql()}\n";
    }

    /**
     * @testWith ["fantastic_books", 1]
     */
    public function testQueryGenreBySlug(string $slug, int $slugableCount)
    {
        $query = $this->query(Genre::class, new Equal('slugables.slug', $slug));

        $this->assertSame(
            'select `genres`.* from `genres` inner join `slugables` on `slugables`.`slugable_id` = `genres`.`id` where `slugables`.`slug` = ? and (`slugables`.`slugable_type` = ?)',
            $query->toSql()
        );

        $genres = $query->get();
        $this->assertSame($slugableCount, $genres->count());
    }

    /**
     * @testWith ["frank_herbert", 1]
     */
    public function testQueryAuthorBySlug(string $slug, int $slugableCount)
    {
        $query = $this->query(Author::class, new Equal('slugables.slug', $slug));

        $this->assertSame(
            'select `authors`.* from `authors` inner join `slugables` on `slugables`.`slugable_id` = `authors`.`id` where `slugables`.`slug` = ? and (`slugables`.`slugable_type` = ?)',
            $query->toSql()
        );

        $authors = $query->get();
        $this->assertSame($slugableCount, $authors->count());
    }

    /**
     * @testWith ["dyuna", 1]
     */
    public function testQueryBookBySlug(string $slug, int $slugableCount)
    {
        $query = $this->query(Book::class, new Equal('slugables.slug', $slug));

        $this->assertSame(
            'select `books`.* from `books` inner join `slugables` on `slugables`.`slugable_id` = `books`.`id` where `slugables`.`slug` = ? and (`slugables`.`slugable_type` = ?)',
            $query->toSql()
        );

        $books = $query->get();
        $this->assertSame($slugableCount, $books->count());
    }

    /**
     * @testWith ["fantastic_books", "bookclub", 1]
     *  ["fantastic", "starylev", 1]
     *  ["missing-slug", "missing-source", 0]
     */
    public function testQueryGenreBySlugWithAdditionalConditions(string $slug, string $source, int $slugableCount)
    {
        $query = $this->query(Genre::class, [
            new Equal('slugables.slug', $slug),
            new Equal('slugables.source', $source),
        ]);

        $this->assertSame(
            'select `genres`.* from `genres` inner join `slugables` on `slugables`.`slugable_id` = `genres`.`id` where `slugables`.`slug` = ? and `slugables`.`source` = ? and (`slugables`.`slugable_type` = ?)',
            $query->toSql()
        );

        $genres = $query->get();
        $this->assertSame($slugableCount, $genres->count());
    }
}
