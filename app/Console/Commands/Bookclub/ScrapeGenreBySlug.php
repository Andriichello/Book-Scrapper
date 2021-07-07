<?php

namespace App\Console\Commands\Bookclub;

use App\Console\Commands\ScrapeFromSourceBySlug;
use App\Jobs\Bookclub\ScrapeGenreBySlugJob;
use App\Models\Genre;
use App\Services\Scrapping\Source;
use Illuminate\Console\Command;

class ScrapeGenreBySlug extends Command
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

    public function handle(): int {
        ScrapeGenreBySlugJob::dispatch($this->argument('slug'));
        return 0;
    }
}

