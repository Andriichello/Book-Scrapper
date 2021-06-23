<?php

namespace App\Scrapping\Parsers\Bookclub;

use App\Scrapping\Parser;
use Symfony\Component\DomCrawler\Crawler;

class AuthorParser extends Parser
{
    protected function parseName(Crawler $crawler): ?string
    {
        $imageCrawler = $crawler->filter('.authorimg img')->first();
        return $this->parseElementAttribute($imageCrawler, 'alt');
    }

    protected function parseImage(Crawler $crawler): ?string
    {
        $imageCrawler = $crawler->filter('.authorimg img')->first();
        return $this->parseElementAttribute($imageCrawler, 'src');
    }

    protected function parseBiography(Crawler $crawler): ?string
    {
        $divCrawlers = $crawler->filter('.auth_bio_txt');
        if ($this->isEmpty($divCrawlers) || $divCrawlers->children()->count() < 2) {
            return null;
        }

        $paragraphCrawlers = $divCrawlers->slice(1, $divCrawlers->count() - 1);

        $biography = '';
        $paragraphCrawlers->each(function ($element) use (&$biography) {
            $biography .= htmlentities($element->html());
        });
        return empty($biography) ? null : $biography;
    }

    protected function validatedData(array $data): ?array
    {
        // todo: add validation rules
        if (empty($data['name'])) {
            return null;
        }
        return parent::validatedData($data);
    }

    protected function parseData(Crawler $crawler, array $params = []): ?array
    {
        $data = [
            'slug' => data_get($params, 'slug'),
            'name' => $this->parseName($crawler),
            'image' => $this->parseImage($crawler),
            'biography' => $this->parseBiography($crawler),
        ];

        return $this->validatedData($data);
    }
}
