<?php

namespace App\Services\Scrapping\Parsers\Bookclub;

use App\Services\Scrapping\Parser;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class AuthorParser extends Parser
{
    protected function parseFullName(Crawler $crawler): ?string
    {
        $imageCrawler = $crawler->filter('.authorimg img')->first();
        return $this->parseElementAttribute($imageCrawler, 'alt');
    }

    protected function splitFullName(?string $fullName): array
    {
        if (empty($fullName)) {
            return [];
        }

        $parts = Str::of($fullName)->explode(' ');
        if ($parts->count() > 1) {
            return ['surname' => $parts->pop(), 'name' => $parts->implode(' ')];
        }

        return ['surname' => null, 'name' => $parts->last()];
    }

    protected function parseImage(Crawler $crawler): ?string
    {
        $imageCrawler = $crawler->filter('.authorimg img')->first();
        $src = $this->parseElementAttribute($imageCrawler, 'src');

        return empty($src) ? null : Str::of($src)->ltrim('/')->start('https://bookclub.ua/');
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
        $data = array_merge(
            [
                'slug' => data_get($params, 'slug'),
                'image' => $this->parseImage($crawler),
                'biography' => $this->parseBiography($crawler),
            ],
            $this->splitFullName($this->parseFullName($crawler)),
        );

        return $this->validatedData($data);
    }
}
