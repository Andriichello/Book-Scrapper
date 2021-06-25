<?php

namespace Tests\Query;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Slug;
use App\Services\Actions\Find;
use App\Services\Actions\Query;
use App\Services\Conditions\Equal;
use App\Services\Queryable;
use Database\Seeders\QueryTestSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Tests\TestCase;

class FindTest extends TestCase
{
    protected Find $find;

    protected function setUp(): void
    {
        parent::setUp();
        $this->find = new Find(new Query());

        $this->seed(QueryTestSeeder::class);
    }

    protected function find(string $model, array|Queryable $conditions): ?Model
    {
        return $this->find->execute($model, Arr::wrap($conditions));
    }

    /**
     * @testWith ["fantastic_books", "bookclub", true]
     * ["missing-slug", "misssing-source", false]
     */
    public function testFindSlug(string $slug, string $source, bool $present)
    {
        $instance = $this->find(Slug::class, [
            new Equal('slug', $slug),
            new Equal('source', $source),
        ]);

        if (!$present) {
            $this->assertNull($instance);
            return;
        }

        $this->assertNotNull($instance);
        $this->assertSame(Slug::class, get_class($instance));
        $this->assertSame($slug, $instance->slug);
        $this->assertSame($source, $instance->source);
    }

    /**
     * @testWith ["«Книжковий Клуб «Клуб Сімейного Дозвілля»", true]
     * ["Неіснуючий клуб", false]
     */
    public function testFindPublisher(string $name, bool $present)
    {
        $instance = $this->find(Publisher::class, [
            new Equal('name', $name),
        ]);

        if (!$present) {
            $this->assertNull($instance);
            return;
        }

        $this->assertNotNull($instance);
        $this->assertSame(Publisher::class, get_class($instance));
        $this->assertSame($name, $instance->name);
    }

    /**
     * @testWith ["Фантастика", true]
     * ["Фентезі", false]
     */
    public function testFindGenre(string $name, bool $present)
    {
        $instance = $this->find(Genre::class, [
            new Equal('name', $name),
        ]);

        if (!$present) {
            $this->assertNull($instance);
            return;
        }

        $this->assertNotNull($instance);
        $this->assertSame(Genre::class, get_class($instance));
        $this->assertSame($name, $instance->name);
    }

    /**
     * @testWith ["Френк", "Герберт", true]
     * ["Джек", "Лондон", false]
     */
    public function testFindAuthor(string $name, string $surname, bool $present)
    {
        $instance = $this->find(Author::class, [
            new Equal('name', $name),
            new Equal('surname', $surname),
        ]);

        if (!$present) {
            $this->assertNull($instance);
            return;
        }

        $this->assertNotNull($instance);
        $this->assertSame(Author::class, get_class($instance));
        $this->assertSame($name, $instance->name);
        $this->assertSame($surname, $instance->surname);
    }

    /**
     * @testWith ["Дюна", true]
     * ["missing-book", false]
     */
    public function testFindBook(string $title, bool $present)
    {
        $instance = $this->find(Book::class, [
            new Equal('title', $title),
        ]);

        if (!$present) {
            $this->assertNull($instance);
            return;
        }

        $this->assertNotNull($instance);
        $this->assertSame(Book::class, get_class($instance));
        $this->assertSame($title, $instance->title);
    }
}
