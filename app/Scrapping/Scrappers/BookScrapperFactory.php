<?php

namespace App\Scrapping\Scrappers;

use App\Scrapping\Scrapper;
use Illuminate\Support\Facades\App;

class BookScrapperFactory implements ScrapperFactory
{
    /**
     * @throws \Exception
     */
    public function initialize(string $source = 'bookclub'): Scrapper
    {
        if ($source === 'bookclub') {
            return App::make(Bookclub\BookScrapper::class);
        }

        throw new \Exception("There is no book scrapper for such web source.");
    }
}
