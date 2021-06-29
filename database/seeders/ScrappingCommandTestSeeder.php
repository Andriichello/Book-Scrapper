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

class ScrappingCommandTestSeeder extends Seeder
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
}
