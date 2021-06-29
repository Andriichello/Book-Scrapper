<?php

namespace Tests\Driven;

use App\Services\Scrapping\Scrappers\Bookclub\AuthorScrapper;
use App\Services\Scrapping\Scrappers\Bookclub\BookScrapper;
use App\Services\Scrapping\Scrappers\Bookclub\GenreScrapper;
use Database\Seeders\ScrappingCommandTestSeeder;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class BookclubScrappingCommandsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ScrappingCommandTestSeeder::class);
    }

    /**
     * @param string $slug
     * @throws \Exception
     * @testWith ["frank_herbert"]
     */
    public function testScrapeAuthorBySlug(string $slug)
    {
        $result = Artisan::call('scrape:bookclub-author', ['slug' => $slug]);
        $this->assertSame(1, $result);
    }

    /**
     * @param string $slug
     * @throws \Exception
     * @testWith ["invalid-author-slug"]
     */
    public function testScrapeAuthorByInvalidSlug(string $slug)
    {
        $this->expectExceptionMessageMatches('/Unable to scrape data by given slug.*/');

        $result = Artisan::call('scrape:bookclub-author', ['slug' => $slug]);
        $this->assertSame(0, $result);
    }

    /**
     * @param string $slug
     * @throws \Exception
     * @testWith ["frank_herbert"]
     */
    public function testScrapeAuthorBySlugWhenItIsAlreadyInDatabase(string $slug)
    {
        $result = Artisan::call('scrape:bookclub-author', ['slug' => $slug]);
        $this->assertSame(1, $result);

        $result = Artisan::call('scrape:bookclub-author', ['slug' => $slug]);
        $this->assertSame(1, $result);
    }

    /**
     * @param string $slug
     * @throws \Exception
     * @testWith ["fantastic_books"]
     */
    public function testScrapeGenreBySlug(string $slug)
    {
        $result = Artisan::call('scrape:bookclub-genre', ['slug' => $slug]);
        $this->assertSame(1, $result);
    }

    /**
     * @param string $slug
     * @throws \Exception
     * @testWith ["invalid-genre-slug"]
     */
    public function testScrapeGenreByInvalidSlug(string $slug)
    {
        $this->expectExceptionMessageMatches('/Unable to scrape data by given slug.*/');

        $result = Artisan::call('scrape:bookclub-genre', ['slug' => $slug]);
        $this->assertSame(0, $result);
    }

    /**
     * @param string $slug
     * @throws \Exception
     * @testWith ["fantastic_books"]
     */
    public function testScrapeGenreBySlugWhenItIsAlreadyInDatabase(string $slug)
    {
        $result = Artisan::call('scrape:bookclub-genre', ['slug' => $slug]);
        $this->assertSame(1, $result);

        $result = Artisan::call('scrape:bookclub-genre', ['slug' => $slug]);
        $this->assertSame(1, $result);
    }

    /**
     * @param string $slug
     * @throws \Exception
     * @testWith ["fantastic_books", "frank_herbert", "dyuna"]
     */
    public function testScrapeBookBySlug(string $genreSlug, string $authorSlug, string $bookSlug)
    {
        $result = Artisan::call('scrape:bookclub-genre', ['slug' => $genreSlug]);
        $this->assertSame(1, $result);

        $result = Artisan::call('scrape:bookclub-author', ['slug' => $authorSlug]);
        $this->assertSame(1, $result);

        $result = Artisan::call('scrape:bookclub-book', ['slug' => $bookSlug]);
        $this->assertSame(1, $result);
    }

    /**
     * @param string $genreSlug
     * @param string $bookSlug
     * @testWith ["fantastic_books", "dyuna"]
     */
    public function testScrapeBookBySlugWhenAuthorIsNotInTheDatabase(string $genreSlug, string $bookSlug)
    {
        $this->seed(ScrappingCommandTestSeeder::class);

        $this->expectExceptionMessageMatches('/Unable to find author with slug:.*/');

        $genreResult = Artisan::call('scrape:bookclub-genre', ['slug' => $genreSlug]);
        $this->assertSame(1, $genreResult);

        $bookResult = Artisan::call('scrape:bookclub-book', ['slug' => $bookSlug]);
        $this->assertSame(0, $bookResult);
    }

    /**
     * @param string $authorSlug
     * @param string $bookSlug
     * @testWith ["frank_herbert", "dyuna"]
     */
    public function testScrapeBookBySlugWhenGenreIsNotInTheDatabase(string $authorSlug, string $bookSlug)
    {
        $this->seed(ScrappingCommandTestSeeder::class);

        $this->expectExceptionMessageMatches('/Unable to find genre with slug:.*/');

        $authorResult = Artisan::call('scrape:bookclub-author', ['slug' => $authorSlug]);
        $this->assertSame(1, $authorResult);

        $bookResult = Artisan::call('scrape:bookclub-book', ['slug' => $bookSlug]);
        $this->assertSame(0, $bookResult);
    }

    /**
     * @param string $bookSlug
     * @testWith ["dyuna"]
     */
    public function testScrapeBookBySlugWhenGenreAndAuthorIsNotInTheDatabase(string $bookSlug)
    {
        $this->seed(ScrappingCommandTestSeeder::class);

        $this->expectExceptionMessageMatches('/Unable to find (genre|author) with slug:.*/');

        $bookResult = Artisan::call('scrape:bookclub-book', ['slug' => $bookSlug]);
        $this->assertSame(0, $bookResult);
    }
}
