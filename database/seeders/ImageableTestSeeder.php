<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Image;
use Illuminate\Database\Seeder;

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
