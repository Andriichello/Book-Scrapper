<?php

namespace App\Scrapping\Parsers\Bookclub;

use App\Scrapping\Parser;
use Symfony\Component\DomCrawler\Crawler;

class AuthorParser extends Parser
{
    public function parse(Crawler $crawler, array $params = []): ?array
    {
        if ($crawler == null) {
            return null;
        }

        return [
            'slug' => data_get($params, 'slug'),
            'name' => $this->parseName($crawler),
            'image' => $this->parseImage($crawler),
            'biography' => $this->parseBiography($crawler),
        ];
    }

    protected function parseName(Crawler $crawler): ?string
    {
        $imageCrawler = $crawler->filter('.authorimg img')->first();
        if ($this->isEmpty($imageCrawler)) {
            return null;
        }

        return $this->parseElementAttribute($imageCrawler, 'alt');
    }

    protected function parseImage(Crawler $crawler): ?string
    {
        $imageCrawler = $crawler->filter('.authorimg img')->first();
        if ($this->isEmpty($imageCrawler)) {
            return null;
        }

        return $this->parseElementAttribute($imageCrawler, 'src');
    }

    protected function parseBiography(Crawler $crawler): ?string
    {
        $divCrawlers = $crawler->filter('.auth_bio_txt')->children();
        if ($divCrawlers->count() < 2) {
            return null;
        }

        $biography = '';
        $divCrawlers->slice(1, $divCrawlers->count() - 1)->each(function ($element) use (&$biography) {
            $biography .= htmlentities($element->html());
        });
        return empty($biography) ? null : $biography;
    }
}
