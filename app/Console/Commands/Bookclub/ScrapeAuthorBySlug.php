<?php

namespace App\Console\Commands\Bookclub;

use App\Models\Author;
use App\Models\Slug;
use App\Services\Actions\FindSlugableAction;
use App\Services\Scrapping\Scrappers\Bookclub\AuthorScrapper;
use App\Services\Scrapping\Source;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

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
    public function handle(FindSlugableAction $find, AuthorScrapper $scrapper)
    {
        $slug = $this->argument('slug');
        $data = $scrapper->scrape(['slug' => $slug]);

        if (empty($data)) {
            $this->error('Unable to parse author with such slug: ' . $slug);
            return 1;
        }

        $author = $this->findOrCreateAuthor($find, $data);
        $this->line(json_encode($author, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }

    protected function findAuthor(FindSlugableAction $find): ?Model {
        $slug = $this->argument('slug');

        try {
            return $find->execute(Author::class, [
                'slug' => $slug,
                'source' => Source::Bookclub
            ]);
        } catch (\Exception $exception) {
            return null;
        }
    }

    protected function findOrCreateAuthor(FindSlugableAction $find, array $data): Model {
        $author = $this->findAuthor($find);
        if (isset($author)) {
            return $author;
        }

        $author = new Author($data);
        if (!$author->save()) {
            throw new \Exception('Unable to save author into the database');
        }

        $slug = new Slug([
            'slug' => $this->argument('slug'),
            'source' => Source::Bookclub
        ]);
        if (!$author->slugs()->save($slug)) {
            throw new \Exception('Unable to save author\'s slug into the database');
        }
        return $author;
    }
}
