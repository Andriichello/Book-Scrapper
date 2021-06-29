<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Publisher;
use App\Models\Slug;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ImageableTestSeeder extends Seeder
{
    /**\
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clearTables();

        Schema::disableForeignKeyConstraints();
        $this->createAuthor();
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

    protected function createAuthor()
    {
        $author = Author::factory()
            ->create([
                'id' => 1,
                'name' => 'Tim',
                'surname' => 'Duncan'
            ]);

        $author->images()->save(new Image([
            'id' => 1,
            'url' => 'test-image-1'
        ]));

        $author->images()->save(new Image([
            'id' => 2,
            'url' => 'test-image-2'
        ]));
    }
}
