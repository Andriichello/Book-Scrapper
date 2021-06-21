<?php

namespace Tests\Driven;

use App\Scrapping\Parsers\AuthorParserFactory;
use App\Scrapping\Scrappers\AuthorScrapperFactory;
use App\Scrapping\Scrappers\BookScrapperFactory;
use App\Scrapping\Scrappers\GenreScrapperFactory;
use Tests\TestCase;

class ScrapperTest extends TestCase
{
    /**
     * @param string $source
     * @param string $slug
     * @throws \Exception
     * @testWith ["bookclub", "frank_herbert"]
     */
    public function testScrapeAuthor(string $source, string $slug) {
        $scrapper = (new AuthorScrapperFactory())->initialize($source);
        $data = $scrapper->scrape(['slug' => $slug]);

        $this->print(['author' => $data]);

        $this->assertNotNull($data);
    }

    /**
     * @param string $source
     * @param string $slug
     * @throws \Exception
     * @testWith ["invalid-source", "invalid-author-slug"]
     */
    public function testScrapeAuthorWithInvalidValues(string $source, string $slug) {
        $this->expectException(\Exception::class);

        $scrapper = (new AuthorScrapperFactory())->initialize($source);
        $data = $scrapper->scrape(['slug' => $slug]);

        $this->print(['author' => $data]);

        $this->assertNotNull($data);
    }

    protected function print(mixed $data) {
        print json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
    }

    /**
     * @param string $source
     * @param string $slug
     * @throws \Exception
     * @testWith ["bookclub", "fantastic_books"]
     */
    public function testScrapeGenre(string $source, string $slug) {
        $scrapper = (new GenreScrapperFactory())->initialize($source);
        $data = $scrapper->scrape(['slug' => $slug]);

        $this->print(['genre' => $data]);

        $this->assertNotNull($data);
    }

    /**
     * @param string $source
     * @param string $slug
     * @throws \Exception
     * @testWith ["bookclub", "dyuna"]
     */
    public function testScrapeBook(string $source, string $slug) {
        $scrapper = (new BookScrapperFactory())->initialize($source);
        $data = $scrapper->scrape(['slug' => $slug]);

        $this->print(['book' => $data]);

        $this->assertNotNull($data);
    }
}
