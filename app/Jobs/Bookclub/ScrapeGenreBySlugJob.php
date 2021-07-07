<?php

namespace App\Jobs\Bookclub;

use App\Jobs\ScrapeFromSourceBySlugJob;
use App\Models\Genre;
use App\Services\Scrapping\Source;

class ScrapeGenreBySlugJob extends ScrapeFromSourceBySlugJob
{
    protected string $model = Genre::class;

    protected string $source = Source::Bookclub;
}
