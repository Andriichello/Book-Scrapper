<?php

namespace App\Scrapping\Parsers\Vsiknygy;

use App\Scrapping\Parser;
use Symfony\Component\DomCrawler\Crawler;

class AuthorParser extends Parser
{
    public function parse(Crawler $crawler): ?array
    {
        if ($crawler == null) {
            return null;
        }

        return [];
    }
}
