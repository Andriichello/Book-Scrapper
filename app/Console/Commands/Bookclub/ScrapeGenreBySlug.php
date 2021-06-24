<?php

namespace App\Console\Commands\Bookclub;

use App\Models\Genre;
use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Conditions\Equal;
use App\Services\Scrapping\Scrappers\Bookclub\GenreScrapper;
use App\Services\Scrapping\Source;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

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
     * @throws \Exception
     */
    public function handle(FindSlugable $find, CreateSlugable $create, GenreScrapper $scrapper)
    {
        $genre = $this->findGenre($find);
        if (isset($genre)) {
            $this->line(json_encode($genre, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return 0;
        }

        $slug = $this->argument('slug');
        $data = $scrapper->scrape(['slug' => $slug]);
        if (empty($data)) {
            $this->error('Unable to parse genre with such slug: ' . $slug);
            return 1;
        }

        $genre = $create->execute(Genre::class, $data, ['slug' => $slug, 'source' => Source::Bookclub]);
        $this->line(json_encode($genre, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }

    protected function findGenre(FindSlugable $find): ?Model
    {
        try {
            return $find->execute(Genre::class, [
                new Equal('slug', $this->argument('slug')),
                new Equal('source', Source::Bookclub),
            ]);
        } catch (\Exception $exception) {
            return null;
        }
    }
}

