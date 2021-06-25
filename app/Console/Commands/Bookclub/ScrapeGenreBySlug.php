<?php

namespace App\Console\Commands\Bookclub;

use App\Console\Commands\ScrapeFromSourceBySlug;
use App\Models\Genre;
use App\Services\Scrapping\Source;

class ScrapeGenreBySlug extends ScrapeFromSourceBySlug
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:bookclub-genre {slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape genre by slug';

    protected string $model = Genre::class;

    protected string $source = Source::Bookclub;
}

