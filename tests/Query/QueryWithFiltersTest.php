<?php

namespace Tests\Query;

use App\Models\Author;
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
use App\Services\Scrapping\Source;
use Database\Seeders\SlugableTestSeeder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class QueryWithFiltersTest extends TestCase
{
    protected Query $query;

    protected function setUp(): void
    {
        parent::setUp();
        $this->query = new Query();
    }

    protected function query(string $model, array|Queryable $conditions): EloquentBuilder|QueryBuilder
    {
        return $this->query->query($model, Arr::wrap($conditions));
    }

    protected function print(EloquentBuilder|QueryBuilder $query): void
    {
        print "query: {$query->toSql()}\n";
    }

    public function testEqual()
    {
        $query = $this->query(Slug::class, [
            new Equal('slug', 'test-slug'),
            new Equal('source', 'test-source'),
        ]);

        $this->assertSame(
            'select * from `slugables` where `slug` = ? and `source` = ?',
            $query->toSql()
        );
    }

    public function testOrEqual()
    {
        $query = $this->query(Slug::class, [
            new Equal('slug', 'test-slug'),
            new OrEqual('source', 'test-source'),
        ]);

        $this->assertSame(
            'select * from `slugables` where `slug` = ? or `source` = ?',
            $query->toSql()
        );
    }

    public function testWhere()
    {
        $query = $this->query(Slug::class, [
            new Where(new Equal('slug', 'test-slug')),
            new Where(new Equal('source', 'test-source')),
        ]);

        $this->assertSame(
            'select * from `slugables` where (`slug` = ?) and (`source` = ?)',
            $query->toSql()
        );
    }

    public function testNestedWhere()
    {
        $query = $this->query(Slug::class, [
            new Where([
                new Equal('slug', 'test-slug'),
                new Where([
                    new Equal('source', 'test-source'),
                    new Equal('slugable_id', 0),
                ]),
            ])
        ]);

        $this->assertSame(
            'select * from `slugables` where (`slug` = ? and (`source` = ? and `slugable_id` = ?))',
            $query->toSql()
        );
    }

    public function testOrWhere()
    {

        $query = $this->query(Slug::class, [
            new Where(new Equal('slug', 'test-slug')),
            new OrWhere(new Equal('source', 'test-source')),
        ]);

        $this->assertSame(
            'select * from `slugables` where (`slug` = ?) or (`source` = ?)',
            $query->toSql()
        );
    }

    public function testNestedOrWhere()
    {
        $query = $this->query(Slug::class, [
            new Where([
                new Equal('slug', 'test-slug'),
                new OrWhere([
                    new Equal('source', 'test-source'),
                    new Equal('slugable_id', 0),
                ]),
            ])
        ]);

        $this->assertSame(
            'select * from `slugables` where (`slug` = ? or (`source` = ? and `slugable_id` = ?))',
            $query->toSql()
        );
    }

    public function testJoinOn()
    {
        $query = $this->query(Author::class, [
            new Join('slugables', [
                new On('slugables.slugable_id', 'authors.id'),
                new On('slugables.slugable_type', 'authors.type'),
            ]),
        ]);

        $this->assertSame(
            'select * from `authors` inner join `slugables` on `slugables`.`slugable_id` = `authors`.`id` and `slugables`.`slugable_type` = `authors`.`type`',
            $query->toSql()
        );
    }

    public function testJoinOrOn()
    {
        $query = $this->query(Author::class, [
            new Join('slugables', [
                new On('slugables.slugable_id', 'authors.id'),
                new OrOn('slugables.slugable_type', 'authors.type'),
            ]),
        ]);

        $this->assertSame(
            'select * from `authors` inner join `slugables` on `slugables`.`slugable_id` = `authors`.`id` or `slugables`.`slugable_type` = `authors`.`type`',
            $query->toSql()
        );
    }
}
