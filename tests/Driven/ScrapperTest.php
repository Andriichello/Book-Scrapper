<?php

namespace Tests\Driven;

use App\Scrapping\Parsers\AuthorParserFactory;
use App\Scrapping\Scrappers\AuthorScrapperFactory;
use Tests\TestCase;

class ScrapperTest extends TestCase
{
    /**
     * @param string $source
     *
     * @testWith ["bookclub"]
     */
    public function testScrapeAuthor(string $source) {
        $scrapper = AuthorScrapperFactory::initialize($source);
        $parser = AuthorParserFactory::initialize($source);

        $data = $scrapper->scrape($parser, ['slug' => 'dyuna']);

        $this->assertNotNull($data);
    }

    /**
     * @param string $source
     *
     * @testWith ["invalid source"]
     */
    public function testScrapeAuthorWithInvalidSource(string $source) {
        $this->expectException(\Exception::class);

        $scrapper = AuthorScrapperFactory::initialize($source);
        $parser = AuthorParserFactory::initialize($source);

        $data = $scrapper->scrape($parser, ['slug' => 'dyuna']);

        $this->assertNotNull($data);
    }
}
