<?php

namespace App\Console\Commands\Bookclub;

use App\Services\Scrapping\Scrappers\Bookclub\GenreScrapper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(GenreScrapper $scrapper)
    {
        $genre = $scrapper->scrape(['slug' => $this->argument('slug')]);

        if (empty($genre)) {
            $this->error('Unable to parse genre with such slug: ' . $this->argument('slug'));
            return 1;
        }

        $this->line(json_encode($genre, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }
}

