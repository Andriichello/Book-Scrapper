<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Slug;
use App\Scrapping\Source;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SlugableTestSeeder extends Seeder
{
    /**\
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $author = Author::factory()
            ->create([
                'name' => 'Tim',
                'surname' => 'Duncan'
            ]);

        $author->slugs()->save(new Slug([
            'slug' => 'tim_duncan',
            'source' => Source::Bookclub,
        ]));

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
    }
}
