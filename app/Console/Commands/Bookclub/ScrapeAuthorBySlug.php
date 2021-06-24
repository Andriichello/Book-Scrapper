<?php

namespace App\Console\Commands\Bookclub;

use App\Console\Commands\Slugable;
use App\Console\Commands\Sourcable;
use App\Models\Author;
use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Scrapping\Scrappers\Bookclub\AuthorScrapper;
use App\Services\Scrapping\Source;
use Illuminate\Console\Command;

class ScrapeAuthorBySlug extends Command
{
    use Sourcable, Slugable;

    public function getSource(): string
    {
        return Source::Bookclub;
    }

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
    public function handle(FindSlugable $find, CreateSlugable $create, AuthorScrapper $scrapper)
    {
        $author = $this->findAuthor($find);
        if (isset($author)) {
            $this->line(json_encode($author, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return 0;
        }

        $slug = $this->argument('slug');
        $data = $scrapper->scrape(['slug' => $slug]);
        if (empty($data)) {
            $this->error('Unable to parse author with such slug: ' . $slug);
            return 1;
        }

        $author = $create->execute(Author::class, $data, ['slug' => $slug, 'source' => Source::Bookclub]);
        $this->line(json_encode($author, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }

    public function findAuthor(FindSlugable $find)
    {
        return $this->findSlugableModel(Author::class, $find, $this->argument('slug'), $this->getSource());
    }
}
