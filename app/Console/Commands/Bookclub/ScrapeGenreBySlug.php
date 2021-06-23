<?php

namespace App\Console\Commands\Bookclub;

use App\Models\Genre;
use App\Models\Slug;
use App\Services\Actions\FindSlugableAction;
use App\Services\Scrapping\Scrappers\Bookclub\GenreScrapper;
use App\Services\Scrapping\Source;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
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
    public function handle(FindSlugableAction $find, GenreScrapper $scrapper)
    {
        $slug = $this->argument('slug');
        $data = $scrapper->scrape(['slug' => $slug]);

        if (empty($data)) {
            $this->error('Unable to parse genre with such slug: ' . $slug);
            return 1;
        }

        $genre = $this->findOrCreateGenre($find, $data);
        $this->line(json_encode($genre, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }

    protected function findGenre(FindSlugableAction $find): ?Model
    {
        $slug = $this->argument('slug');

        try {
            return $find->execute(Genre::class, [
                'slug' => $slug,
                'source' => Source::Bookclub
            ]);
        } catch (\Exception $exception) {
            return null;
        }
    }

    protected function findOrCreateGenre(FindSlugableAction $find, array $data): Model
    {
        $genre = $this->findGenre($find);
        if (isset($genre)) {
            return $genre;
        }

        $genre = new Genre($data);
        if (!$genre->save()) {
            throw new \Exception('Unable to save author into the database');
        }

        $slug = new Slug([
            'slug' => $this->argument('slug'),
            'source' => Source::Bookclub
        ]);
        if (!$genre->slugs()->save($slug)) {
            throw new \Exception('Unable to save author\'s slug into the database');
        }
        return $genre;
    }
}

