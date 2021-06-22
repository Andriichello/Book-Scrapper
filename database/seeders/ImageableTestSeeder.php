<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Slug;
use App\Scrapping\Source;
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
        $author = Author::factory()
            ->create([
                'name' => 'Tim',
                'surname' => 'Duncan'
            ]);

        $author->images()->save(new Image([
            'url' => 'image url'
        ]));
    }
}
