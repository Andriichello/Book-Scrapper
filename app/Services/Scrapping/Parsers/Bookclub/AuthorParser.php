<?php

namespace App\Services\Scrapping\Parsers\Bookclub;

use App\Services\Scrapping\Parser;
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
        $biographyCrawler = $crawler->filter('.auth_bio_txt');
        if ($this->isEmpty($biographyCrawler)) {
            return null;
        }

        $paragraphs = $biographyCrawler->children()
            ->reduce(fn(Crawler $c, $i) => $i > 0)
            ->each(function ($paragraphCrawler) {
                return htmlentities($paragraphCrawler->outerHtml());
            });

        return empty($paragraphs) ? null : implode($paragraphs);
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
