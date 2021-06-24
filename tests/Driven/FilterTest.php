<?php

namespace Tests\Driven;

use App\Models\Author;
use App\Models\Slug;
use App\Services\Actions\Query;
use App\Services\Conditions\Equal;
use App\Services\Conditions\On;
use App\Services\Filters\Join;
use App\Services\Filters\Where;
use App\Services\Scrapping\Source;
use Database\Seeders\SlugableTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SlugableTestSeeder::class);
    }

    public function testWhere()
    {
        $where = new Where([
            new Equal('slug', 'tim_duncan'),
            new Equal('source', Source::Bookclub),
        ]);

        $action = new Query();
        $query = $action->query(Slug::class, [$where]);

        print "query: {$query->toSql()}\n";

        $this->assertTrue(true);
    }

    public function testJoin()
    {
        $join = new Join(
            'slugables',
            new On('slugables.slugable_id', 'authors.id')
        );
        $where = new Where(new Equal('slugables.slugable_type', 'authors'));

        $action = new Query();
        $query = $action->query(Author::class, [$join, $where]);

        $author = $query->first();
        print "query: " . get_class($author) . "\n";


        print "query: {$query->toSql()}\n";
        print "queried: " . json_encode($query->get(), JSON_PRETTY_PRINT) . "\n";

        $this->assertTrue(true);
    }
}
