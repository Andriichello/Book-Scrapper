<?php

namespace App\Console\Commands\Bookclub;

use App\Scrapping\Scrappers\Bookclub\AuthorScrapper;
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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(AuthorScrapper $scrapper)
    {
        $author = $scrapper->scrape(['slug' => $this->argument('slug')]);

        if (empty($author)) {
            $this->error('Unable to parse author with such slug: ' . $this->argument('slug'));
            return 1;
        }

        $this->line(json_encode($author, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }
}
