<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

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
    public function handle()
    {
        try {
            return $this->perform();
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            return $exception->getCode();
        }
    }

    /**
     * @throws \Exception
     */
    protected function perform(): int
    {
        $scrapper = App::make($this->option('source') . '-author-scrapper');
        $author = $scrapper->scrape(['slug' => $this->argument('slug')]);

        if (empty($author)) {
            $this->error('Unable to parse author with such slug: ' . $this->argument('slug'));
            return 1;
        }

        $this->line(json_encode($author, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }
}
