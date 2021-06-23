<?php

namespace App\Scrapping\Parsers\Bookclub;

use App\Scrapping\Parser;
use Symfony\Component\DomCrawler\Crawler;

class GenreParser extends Parser
{
    protected function parseName(Crawler $crawler): ?string
    {
        $genreCrawler = $crawler->filter('.sec-podpunkt-act')->first();
        return $this->isEmpty($genreCrawler) ? null : $genreCrawler->text();
    }

    protected function parseDescription(Crawler $crawler): ?string
    {
        $descriptionCrawler = $crawler->filter('.cat-descript .cat-descr')->first();
        return $this->isEmpty($descriptionCrawler) ? null : preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $descriptionCrawler->html());
    }

    protected function parseData(Crawler $crawler, array $params = []): ?array
    {
        $data = [
            'slug' => data_get($params, 'slug'),
            'name' => $this->parseName($crawler),
            'description' => $this->parseDescription($crawler),
        ];

        return $this->validatedData($data);
    }

    protected function validatedData(array $data): ?array
    {
        // todo: add validation rules
        if (empty($data['name'])) {
            return null;
        }

         return $data;
    }
}
