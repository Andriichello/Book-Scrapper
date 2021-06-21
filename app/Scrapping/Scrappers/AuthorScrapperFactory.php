<?php

namespace App\Scrapping\Scrappers;

use App\Scrapping\Scrapper;

class AuthorScrapperFactory
{
    /**
     * @throws \Exception
     */
    public static function initialize(string $source = 'bookclub'): Scrapper
    {
        if ($source === 'bookclub') {
            return new Bookclub\AuthorScrapper();
        } else if ($source === 'vsiknygy') {
            return new Vsiknygy\AuthorScrapper();
        } else if ($source === 'yakaboo') {
            return new Yakaboo\AuthorScrapper();
        }

        throw new \Exception("There is no author scrapper for such web source.");
    }
}
