<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class ScrapeGenreBySlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:genre {slug} {--source=bookclub}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape genre by slug';

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
        $scrapper = App::make($this->option('source') . '-genre-scrapper');
        $genre = $scrapper->scrape(['slug' => $this->argument('slug')]);

        if (empty($genre)) {
            $this->error('Unable to parse genre with such slug: ' . $this->argument('slug'));
            return 1;
        }

        $this->line(json_encode($genre, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }
}

