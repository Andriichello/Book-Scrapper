<?php

namespace App\Scrapping\Parsers\Bookclub;

use App\Scrapping\Parser;
use Symfony\Component\DomCrawler\Crawler;

class GenreParser extends Parser
{
    public function parse(Crawler $crawler, array $params = []): ?array
    {
        if ($crawler == null) {
            return null;
        }

        return [
            'slug' => data_get($params, 'slug'),
            'name' => $this->parseName($crawler),
            'description' => $this->parseDescription($crawler),
        ];
    }

    protected function parseName(Crawler $crawler): ?string
    {
        $genreCrawler = $crawler->filter('.sec-podpunkt-act')->first();
        if ($this->isEmpty($genreCrawler)) {
            return null;
        }

        return $genreCrawler->text();
    }

    protected function parseDescription(Crawler $crawler): ?string
    {
        $descriptionCrawler = $crawler->filter('.cat-descript .cat-descr')->first();
        if ($this->isEmpty($descriptionCrawler)) {
            return null;
        }

        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $descriptionCrawler->html());
    }
}
