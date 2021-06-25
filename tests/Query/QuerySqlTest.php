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
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Tests\TestCase;

class QuerySqlTest extends TestCase
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

    public function testEqualQuery()
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

    public function testOrEqualQuery()
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

    public function testWhereQuery()
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

    public function testNestedWhereQuery()
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

    public function testOrWhereQuery()
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

    public function testNestedOrWhereQuery()
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

    public function testJoinOnQuery()
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

    public function testJoinOrOnQuery()
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
