<?php

namespace Tests\Driven;

use App\Services\Scrapping\Scrappers\Bookclub\AuthorScrapper;
use App\Services\Scrapping\Scrappers\Bookclub\BookScrapper;
use App\Services\Scrapping\Scrappers\Bookclub\GenreScrapper;
use Tests\TestCase;

class BookclubScrappersTest extends TestCase
{
    /**
     * @param string $slug
     * @throws \Exception
     * @testWith ["frank_herbert"]
     */
    public function testAuthorScrapper(string $slug)
    {
        $scrapper = $this->app->make(AuthorScrapper::class);
        $data = $scrapper->scrape(['slug' => $slug]);

        $this->assertNotEmpty($data);
        $this->assertNotEmpty($data['name']);
        $this->assertSame($slug, $data['slug']);
    }

    /**
     * @param string $slug
     * @throws \Exception
     * @testWith [ "invalid-author-slug"]
     */
    public function testAuthorScrapperWithInvalidSlug(string $slug)
    {
        $scrapper = $this->app->make(AuthorScrapper::class);
        $data = $scrapper->scrape(['slug' => $slug]);

        $this->assertNull($data);
    }

    /**
     * @param string $slug
     * @throws \Exception
     * @testWith ["fantastic_books"]
     */
    public function testGenreScrapper(string $slug)
    {
        $scrapper = $this->app->make(GenreScrapper::class);
        $data = $scrapper->scrape(['slug' => $slug]);

        $this->assertNotEmpty($data);
        $this->assertNotEmpty($data['name']);
        $this->assertSame($slug, $data['slug']);
    }

    /**
     * @param string $slug
     * @throws \Exception
     * @testWith ["dyuna"]
     */
    public function testBookScrapper(string $slug)
    {
        $scrapper = $this->app->make(BookScrapper::class);
        $data = $scrapper->scrape(['slug' => $slug]);

        $this->assertNotEmpty($data);
        $this->assertNotEmpty($data['title']);
        $this->assertSame($slug, $data['slug']);
        $this->assertIsArray($data['genre']);
        $this->assertNotEmpty($data['genre']['slug']);
        $this->assertIsArray($data['author']);
        $this->assertNotEmpty($data['author']['slug']);
    }
}
