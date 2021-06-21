<?php

namespace App\Console\Commands;

use App\Scrapping\Scrappers\AuthorScrapperFactory;
use Illuminate\Console\Command;

class ScrapeAuthorBySlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:author {slug} {--source=bookclub}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape author by slug';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(AuthorScrapperFactory $factory)
    {
        try {
            return $this->perform($factory);
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            return $exception->getCode();
        }
    }

    /**
     * @throws \Exception
     */
    protected function perform(AuthorScrapperFactory $factory): int
    {
        $scrapper = $factory->initialize($this->option('source'));
        $book = $scrapper->scrape(['slug' => $this->argument('slug')]);

        if (empty($book)) {
            return 1;
        }

        $this->line(json_encode($book, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }
}
