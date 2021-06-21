<?php

namespace App\Scrapping;

use Symfony\Component\DomCrawler\Crawler;

abstract class Parser
{
    public function parse(Crawler $crawler): ?array
    {
        if ($crawler === null) {
            return null;
        }

        return [];
    }
}
