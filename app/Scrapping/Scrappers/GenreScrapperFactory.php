<?php

namespace App\Scrapping\Scrappers;

use App\Scrapping\Scrapper;
use Illuminate\Support\Facades\App;

class GenreScrapperFactory implements ScrapperFactory
{
    /**
     * @throws \Exception
     */
    public function initialize(string $source = 'bookclub'): Scrapper
    {
        if ($source === 'bookclub') {
            return App::make(Bookclub\GenreScrapper::class);
        }

        throw new \Exception("There is no genre scrapper for such web source.");
    }
}
