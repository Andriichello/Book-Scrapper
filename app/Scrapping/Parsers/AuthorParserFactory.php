<?php

namespace App\Scrapping\Parsers;

use App\Scrapping\Parser;
use Symfony\Component\DomCrawler\Crawler;

class AuthorParserFactory
{
    public static function initialize(string $source = 'bookclub'): Parser
    {
        if ($source === 'bookclub') {
            return new Bookclub\AuthorParser();
        } else if ($source === 'vsiknygy') {
            return new Vsiknygy\AuthorParser();
        } else if ($source === 'yakaboo') {
            return new Yakaboo\AuthorParser();
        }

        throw new \Exception("There is no author parser for such web source.");
    }
}
