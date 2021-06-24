<?php

namespace App\Console\Commands\Bookclub;

use App\Console\Commands\Slugable;
use App\Console\Commands\Sourcable;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Conditions\Equal;
use App\Services\Scrapping\Scrappers\Bookclub\BookScrapper;
use App\Services\Scrapping\Source;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ScrapeBookBySlug extends Command
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
    public function handle(FindSlugable $find, CreateSlugable $create, BookScrapper $scrapper)
    {
        $book = $this->findBook($find, $this->argument('slug'));
        if (isset($book)) {
            $this->line(json_encode($book, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return 0;
        }

        $data = $scrapper->scrape(['slug' => $this->argument('slug')]);
        if (empty($data)) {
            $this->error('Unable to parse book with such slug: ' . $this->argument('slug'));
            return 1;
        }

        $book = $this->createBook($data, $find, $create);
        $this->line(json_encode($book, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return 0;
    }

    protected function findGenre(FindSlugable $find, string $slug): ?Model
    {
        return $this->findSlugableModel(Genre::class, $find, $slug, $this->getSource());
    }

    protected function findAuthor(FindSlugable $find, string $slug): ?Model
    {
        return $this->findSlugableModel(Author::class, $find, $slug, $this->getSource());
    }

    protected function findBook(FindSlugable $find, string $slug): ?Model
    {
        return $this->findSlugableModel(Book::class, $find, $slug, $this->getSource());
    }

    protected function createSlugableModel(string $model, array $data, CreateSlugable $create, string $slug): ?Model
    {
        try {
            return $create->execute($model, $data, [
                'slug' => $slug,
                'source' => Source::Bookclub
            ]);
        } catch (\Exception $exception) {
            return null;
        }
    }

    protected function findPublisher(string $name): ?Model
    {
        return Publisher::all()
            ->where('name', $name)
            ->first();
    }

    protected function createPublisher(string $name): ?Model
    {
        $publisher = new Publisher();
        $publisher->fill([
            'name' => $name,
        ]);
        return $publisher->save() ? $publisher : null;
    }

    protected function findOrCreatePublisher(string $name): Model
    {
        return $this->findPublisher($name) ?? $this->createPublisher($name);
    }

    protected function createBook(array $data, FindSlugable $find, CreateSlugable $create): Book
    {
        $genreData = Arr::pull($data, 'genre');
        $genre = $this->findGenre($find, Arr::get($genreData, 'slug'));
        if (empty($genre)) {
            throw new \Exception('Unable to find or create genre with slug: ' . Arr::get($genreData, 'slug'));
        }

        $authorData = Arr::pull($data, 'author');
        $author = $this->findAuthor($find, Arr::get($authorData, 'slug'));
        if (empty($author)) {
            new \Exception('Unable to find or create author with slug: ' . Arr::get($authorData, 'slug'));
        }

        $publisher = $this->findOrCreatePublisher(Arr::pull($data, 'publisher'));

        Arr::set($data, 'details', json_encode(Arr::get($data, 'details'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        Arr::set($data, 'publisher_id', $publisher->id);

        $book = $this->createSlugableModel(Book::class, $data, $create, $this->argument('slug'));
        if (!$book->genres()->save($genre)) {
            throw new \Exception('Unable to attach genre to the book.');
        }
        if (!$book->authors()->save($author)) {
            throw new \Exception('Unable to attach author to the book.');
        }
        return $book;
    }
}
