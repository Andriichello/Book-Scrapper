<?php

namespace Tests\Query;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Services\Actions\QuerySlugable;
use App\Services\Conditions\Equal;
use App\Services\Filters\Where;
use App\Services\Queryable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Tests\TestCase;

class QuerySlugableSqlTest extends TestCase
{
    protected QuerySlugable $query;

    protected function setUp(): void
    {
        parent::setUp();
        $this->query = new QuerySlugable();
    }

    protected function query(string $model, array|Queryable $conditions = []): EloquentBuilder|QueryBuilder
    {
        return $this->query->query($model, Arr::wrap($conditions));
    }

    protected function print(EloquentBuilder|QueryBuilder $query): void
    {
        print "query: {$query->toSql()}\n";
    }

    public function testGenreQuery()
    {
        $query = $this->query(Genre::class);

        $this->assertSame(
            'select `genres`.* from `genres` inner join `slugables` on `slugables`.`slugable_id` = `genres`.`id` where (`slugables`.`slugable_type` = ?)',
            $query->toSql()
        );
    }

    public function testAuthorQuery()
    {
        $query = $this->query(Author::class);

        $this->assertSame(
            'select `authors`.* from `authors` inner join `slugables` on `slugables`.`slugable_id` = `authors`.`id` where (`slugables`.`slugable_type` = ?)',
            $query->toSql()
        );
    }

    public function testBookQuery()
    {
        $query = $this->query(Book::class);

        $this->assertSame(
            'select `books`.* from `books` inner join `slugables` on `slugables`.`slugable_id` = `books`.`id` where (`slugables`.`slugable_type` = ?)',
            $query->toSql()
        );
    }

    public function testSlugableQueryWithAdditionalConditions()
    {
        $query = $this->query(Genre::class, [
            new Where(new Equal('genres.name', 'test-name'))
        ]);

        $this->assertSame(
            'select `genres`.* from `genres` inner join `slugables` on `slugables`.`slugable_id` = `genres`.`id` where (`genres`.`name` = ?) and (`slugables`.`slugable_type` = ?)',
            $query->toSql()
        );
    }
}
