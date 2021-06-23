<?php

namespace App\Services\Scrapping\Scrappers\Bookclub;

use App\Services\Scrapping\Parser;
use App\Services\Scrapping\Scrapper;
use Illuminate\Support\Str;

class BookScrapper extends Scrapper
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
