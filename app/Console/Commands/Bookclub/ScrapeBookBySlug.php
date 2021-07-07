<?php

namespace App\Console\Commands\Bookclub;

use App\Console\Commands\ScrapeFromSourceBySlug;
use App\Jobs\Bookclub\ScrapeAuthorBySlugJob;
use App\Jobs\Bookclub\ScrapeBookBySlugJob;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Services\Actions\FindSlugable;
use App\Services\Scrapping\Source;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ScrapeBookBySlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:bookclub-book {slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape book by slug';

    public function handle(): int {
        ScrapeBookBySlugJob::dispatch($this->argument('slug'));
        return 0;
    }
}
