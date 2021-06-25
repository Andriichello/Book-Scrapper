<?php

namespace App\Console\Commands\Bookclub;

use App\Console\Commands\ScrapeFromSourceBySlug;
use App\Models\Author;
use App\Services\Scrapping\Source;

class ScrapeAuthorBySlug extends ScrapeFromSourceBySlug
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:bookclub-author {slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape author by slug';

    protected string $model = Author::class;

    protected string $source = Source::Bookclub;
}
