<?php

namespace App\Jobs\Bookclub;

use App\Jobs\ScrapeFromSourceBySlugJob;
use App\Models\Author;
use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Scrapping\Scrapper;
use App\Services\Scrapping\Scrappers\Bookclub\AuthorScrapper;
use App\Services\Scrapping\Source;

class ScrapeAuthorBySlugJob extends ScrapeFromSourceBySlugJob
{
    protected string $model = Author::class;

    protected string $source = Source::Bookclub;
}
