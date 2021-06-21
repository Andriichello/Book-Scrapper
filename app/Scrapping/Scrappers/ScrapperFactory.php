<?php

namespace App\Scrapping\Scrappers;

use App\Scrapping\Scrapper;

interface ScrapperFactory
{
    /**
     * @throws \Exception
     */
    public function initialize(string $source): Scrapper;
}
