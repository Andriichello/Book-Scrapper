<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Publisher;
use App\Models\Slug;
use App\Services\Scrapping\Source;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SlugableTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->clearTables();
        $this->createAuthor();
        $this->createGenre();
        Schema::enableForeignKeyConstraints();
    }

    protected function clearTables(): void
    {
        Slug::query()->delete();
        Image::query()->delete();
        Book::query()->delete();
        Genre::query()->delete();
        Author::query()->delete();
        Publisher::query()->delete();
    }

    protected function createAuthor(): Author
    {
        $author = Author::factory()
            ->create([
                'id' => 1,
                'name' => 'Tim',
                'surname' => 'Duncan'
            ]);

        $author->slugs()->save(new Slug([
            'id' => 1,
            'slug' => 'tim_duncan',
            'source' => Source::Bookclub,
        ]));

        return $author;
    }

    protected function createGenre(): Genre
    {
        $genre = Genre::factory()
            ->create([
                'name' => 'Фантастика',
            ]);

        $genre->slugs()->save(new Slug([
            'slug' => 'fantastic_books',
            'source' => Source::Bookclub,
        ]));

        $genre->slugs()->save(new Slug([
            'slug' => 'fantastic',
            'source' => Source::Starylev,
        ]));

        return $genre;
    }
}
