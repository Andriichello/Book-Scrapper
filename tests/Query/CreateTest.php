<?php

namespace Tests\Query;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Slug;
use App\Services\Actions\Create;
use App\Services\Actions\Find;
use App\Services\Actions\Query;
use App\Services\Conditions\Equal;
use App\Services\Queryable;
use Database\Seeders\QueryTestSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CreateTest extends TestCase
{
    protected Create $create;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create = new Create();

        $this->seed(QueryTestSeeder::class);
    }

    protected function create(string $model, array $params): ?Model
    {
        return $this->create->execute($model, $params);
    }

    protected function getSlugCreationData(): array
    {
        return [
            "slug" => "test-slug",
            "source" => "test-source",
            "slugable_id" => 1,
            "slugable_type" => "books"
        ];
    }

    public function testCreateSlug()
    {
        $attributes = $this->getSlugCreationData();
        $instance = $this->create(Slug::class, $attributes);

        $this->assertNotNull($instance);
        $this->assertSame(Slug::class, get_class($instance));
        $this->assertDatabaseHas(Slug::class, $attributes);
    }

    protected function getPublisherCreationData(): array
    {
        return [
            "name" => "Unreal Publisher",
        ];
    }

    public function testCreatePublisher()
    {
        $attributes = $this->getPublisherCreationData();
        $instance = $this->create(Publisher::class, $attributes);

        $this->assertNotNull($instance);
        $this->assertSame(Publisher::class, get_class($instance));
        $this->assertDatabaseHas(Publisher::class, $attributes);
    }

    protected function getGenreCreationData(): array
    {
        return [
            "name" => "Imaginary",
            "description" => "This is an Imaginary genre",
        ];
    }

    public function testCreateGenre()
    {
        $attributes = $this->getGenreCreationData();
        $instance = $this->create(Genre::class, $attributes);

        $this->assertNotNull($instance);
        $this->assertSame(Genre::class, get_class($instance));
        $this->assertDatabaseHas(Genre::class, $attributes);
    }

    protected function getAuthorCreationData(): array
    {
        return [
            "name" => "John",
            "surname" => "Doe",
            "biography" => "John Doe is an imaginary name, which is frequently used by programmers all around the world.",
        ];
    }

    public function testCreateAuthor()
    {
        $attributes = $this->getAuthorCreationData();
        $instance = $this->create(Author::class, $attributes);

        $this->assertNotNull($instance);
        $this->assertSame(Author::class, get_class($instance));
        $this->assertDatabaseHas(Author::class, $attributes);
    }
}
