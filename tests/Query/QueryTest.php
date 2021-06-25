<?php

namespace Tests\Query;

use App\Models\Author;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Slug;
use App\Services\Actions\Query;
use App\Services\Conditions\Equal;
use App\Services\Conditions\On;
use App\Services\Conditions\OrEqual;
use App\Services\Conditions\OrOn;
use App\Services\Filters\Join;
use App\Services\Filters\OrWhere;
use App\Services\Filters\Where;
use App\Services\Queryable;
use Database\Seeders\QueryTestSeeder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class QueryTest extends TestCase
{
    protected Query $query;

    protected function setUp(): void
    {
        parent::setUp();
        $this->query = new Query();

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

    public function testQueryPublishers() {
        $query = $this->query(Publisher::class, [
            new Equal('name', '«Книжковий Клуб «Клуб Сімейного Дозвілля»')
        ]);

        $this->assertSame(
            'select * from `publishers` where `name` = ?',
            $query->toSql()
        );

        $publishers = $query->get();
        $this->assertSame(1, count($publishers));
    }

    public function testQueryAuthors() {
        $query = $this->query(Author::class, [
            new Equal('name', 'Френк'),
            new Equal('surname', 'Герберт')
        ]);

        $this->assertSame(
            'select * from `authors` where `name` = ? and `surname` = ?',
            $query->toSql()
        );

        $authors = $query->get();
        $this->assertSame(1, count($authors));
    }

    public function testQueryGenre() {
        $query = $this->query(Genre::class, [
            new Equal('name', 'Фантастика'),
        ]);

        $this->assertSame(
            'select * from `genres` where `name` = ?',
            $query->toSql()
        );

        $genres = $query->get();
        $this->assertSame(1, count($genres));
    }

    /**
     * @testWith ["authors", 1]
     *           ["genres", 2]
     *           ["books", 1]
     */
    public function testQuerySlug(string $slugableType, int $slugsCount) {
        $query = $this->query(Slug::class, [
            new Equal('slugable_type', $slugableType),
        ]);

        $this->assertSame(
            'select * from `slugables` where `slugable_type` = ?',
            $query->toSql()
        );

        $slugs = $query->get();
        $this->assertSame($slugsCount, count($slugs));
    }
}
