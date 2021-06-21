<?php

namespace App\Scrapping\Scrappers\Bookclub;

use App\Scrapping\Scrapper;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class AuthorScrapper extends Scrapper
{
    public function __construct()
    {
        parent::__construct('https://bookclub.ua/', 'get');
    }

    protected function getUrl(array $params = []): string
    {
        $url = parent::getUrl($params);

        if (empty($params['slug'])) {
            return $url;
        }

        return Str::of($url)->finish('/')->append($params['slug']);
    }
}
