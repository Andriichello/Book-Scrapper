<?php

namespace App\Scrapping\Scrappers;

use App\Scrapping\Scrapper;
use Illuminate\Support\Facades\App;

class AuthorScrapperFactory implements ScrapperFactory
{
    /**
     * @throws \Exception
     */
    public function initialize(string $source = 'bookclub'): Scrapper
    {
        if ($source === 'bookclub') {
            return App::make(Bookclub\AuthorScrapper::class);
        }

        throw new \Exception("There is no author scrapper for such web source.");
    }
}
