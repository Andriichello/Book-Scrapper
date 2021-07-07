<?php

namespace App\Console\Commands\Bookclub;

use App\Console\Commands\ScrapeFromSourceBySlug;
use App\Jobs\Bookclub\ScrapeAuthorBySlugJob;
use App\Models\Author;
use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Scrapping\Scrapper;
use App\Services\Scrapping\Scrappers\Bookclub\AuthorScrapper;
use App\Services\Scrapping\Source;
use Illuminate\Console\Command;

class ScrapeAuthorBySlug extends Command
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

    public function handle(): int {
        ScrapeAuthorBySlugJob::dispatch($this->argument('slug'));
        return 0;
    }
}
