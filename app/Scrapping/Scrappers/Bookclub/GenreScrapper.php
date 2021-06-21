<?php

namespace App\Scrapping\Scrappers\Bookclub;

use App\Scrapping\Parser;
use App\Scrapping\Scrapper;
use Illuminate\Support\Str;

class GenreScrapper extends Scrapper
{
    public function __construct(Parser $parser)
    {
        parent::__construct('https://bookclub.ua/catalog/books/', 'get', $parser);
    }

    public function getUrl(array $params = []): string
    {
        $url = parent::getUrl($params);

        if (empty($params['slug'])) {
            return $url;
        }

        return Str::of($url)->finish('/')->append($params['slug']);
    }
}
