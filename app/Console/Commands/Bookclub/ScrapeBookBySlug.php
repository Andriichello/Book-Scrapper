<?php

namespace App\Console\Commands\Bookclub;

use App\Services\Scrapping\Scrappers\Bookclub\BookScrapper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(BookScrapper $scrapper)
    {
        $slug = $this->argument('slug');
        $data = $scrapper->scrape(['slug' => $slug]);

        if (empty($data)) {
            $this->error('Unable to parse book with such slug: ' . $slug);
            return 1;
        }

        $this->line(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }
}
