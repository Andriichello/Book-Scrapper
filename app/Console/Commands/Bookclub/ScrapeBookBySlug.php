<?php

namespace App\Console\Commands\Bookclub;

use App\Console\Commands\ScrapeFromSourceBySlug;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Services\Actions\FindSlugable;
use App\Services\Scrapping\Source;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ScrapeBookBySlug extends ScrapeFromSourceBySlug
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

    protected string $model = Book::class;

    protected string $source = Source::Bookclub;

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

    protected function findGenre(FindSlugable $find, string $slug): ?Model
    {
        return $this->findSlugableModel(Genre::class, $find, $slug, $this->source);
    }

    protected function findAuthor(FindSlugable $find, string $slug): ?Model
    {
        return $this->findSlugableModel(Author::class, $find, $slug, $this->source);
    }

    protected function createModel(array $data): Book
    {
        $genreData = Arr::pull($data, 'genre');
        $genre = $this->findGenre($this->find, Arr::get($genreData, 'slug'));
        if (empty($genre)) {
            throw new \Exception('Unable to find genre with slug: ' . Arr::get($genreData, 'slug'));
        }

        $authorData = Arr::pull($data, 'author');
        $author = $this->findAuthor($this->find, Arr::get($authorData, 'slug'));
        if (empty($author)) {
            new \Exception('Unable to find author with slug: ' . Arr::get($authorData, 'slug'));
        }

        $publisher = $this->findOrCreatePublisher(Arr::pull($data, 'publisher'));

        Arr::set($data, 'details', json_encode(Arr::get($data, 'details'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        Arr::set($data, 'publisher_id', $publisher->id);

        $book = $this->createSlugableModel(Book::class, $data, $this->create, $this->argument('slug'), $this->source);
        if (!$book->genres()->save($genre)) {
            throw new \Exception('Unable to attach genre to the book.');
        }
        if (!$book->authors()->save($author)) {
            throw new \Exception('Unable to attach author to the book.');
        }
        return $book;
    }

    protected function updateModel(Model|Book $book, array $data): bool
    {
        $updated = parent::updateModel($book, $data);
        if (!$updated) {
            return false;
        }

        $genreData = Arr::pull($data, 'genre');
        $authorData = Arr::pull($data, 'author');
        $publisher = $this->findOrCreatePublisher(Arr::pull($data, 'publisher'));

        Arr::set($data, 'details', json_encode(Arr::get($data, 'details'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        Arr::set($data, 'publisher_id', $publisher->id);
        return true;
    }

}
