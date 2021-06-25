<?php

namespace Tests\Query;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Slug;
use App\Services\Actions\Create;
use App\Services\Actions\CreateSlugable;
use App\Services\Actions\Find;
use App\Services\Actions\Query;
use App\Services\Conditions\Equal;
use App\Services\Queryable;
use Database\Seeders\QueryTestSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CreateSluggableTest extends TestCase
{
    protected CreateSlugable $create;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create = new CreateSlugable(new Find(new Query()));

        $this->seed(QueryTestSeeder::class);
    }

    protected function create(string $model, array $params, array $slug): ?Model
    {
        return $this->create->execute($model, $params, $slug);
    }

    protected function getGenreCreationData(): array
    {
        return [
            [
                "name" => "Imaginary",
                "description" => "This is an Imaginary genre",
            ],
            [
                "slug" => "imaginary_books",
                "source" => "bookclub",
            ]
        ];
    }

    public function testCreateGenre()
    {
        [$attributes, $slug] = $this->getGenreCreationData();
        $instance = $this->create(Genre::class, $attributes, $slug);

        $this->assertNotNull($instance);
        $this->assertSame(Genre::class, get_class($instance));
        $this->assertDatabaseHas(Genre::class, $attributes);
        $this->assertDatabaseHas(Slug::class, $slug);
    }

    protected function getAuthorCreationData(): array
    {
        return [
            [
                "name" => "John",
                "surname" => "Doe",
                "biography" => "John Doe is an imaginary name, which is frequently used by programmers all around the world.",
            ],
            [
                "slug" => "john_doe",
                "source" => "bookclub",
            ]
        ];
    }

    public function testCreateAuthor()
    {
        [$attributes, $slug] = $this->getAuthorCreationData();
        $instance = $this->create(Author::class, $attributes, $slug);

        $this->assertNotNull($instance);
        $this->assertSame(Author::class, get_class($instance));
        $this->assertDatabaseHas(Author::class, $attributes);
        $this->assertDatabaseHas(Slug::class, $slug);
    }

    protected function getBookCreationData(): array
    {
        return [
            [
                "title" => "White fang",
                "price" => 100,
                "club_price" => 80,
                "currency" => "uah",
                "language" => "english",
                "publisher_id" => 1,
                "isbn" => 'testing-isbn',
            ],
            [
                "slug" => "white_fang",
                "source" => "bookclub",
            ]
        ];
    }

    public function testCreateBook()
    {
        [$attributes, $slug] = $this->getBookCreationData();
        $instance = $this->create(Book::class, $attributes, $slug);

        $this->assertNotNull($instance);
        $this->assertSame(Book::class, get_class($instance));
        $this->assertDatabaseHas(Book::class, $attributes);
        $this->assertDatabaseHas(Slug::class, $slug);
    }
}
