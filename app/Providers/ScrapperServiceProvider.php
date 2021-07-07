<?php

namespace App\Providers;

use App\Jobs\Bookclub\ScrapeAuthorBySlugJob;
use App\Jobs\Bookclub\ScrapeBookBySlugJob;
use App\Jobs\Bookclub\ScrapeGenreBySlugJob;
use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Scrapping\Parsers;
use App\Services\Scrapping\Scrappers;
use Illuminate\Support\ServiceProvider;

class ScrapperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Scrappers\Bookclub\BookScrapper::class, function () {
            return new Scrappers\Bookclub\BookScrapper($this->app->make(Parsers\Bookclub\BookParser::class));
        });

        $this->app->bind(Scrappers\Bookclub\AuthorScrapper::class, function () {
            return new Scrappers\Bookclub\AuthorScrapper($this->app->make(Parsers\Bookclub\AuthorParser::class));
        });

        $this->app->bind(Scrappers\Bookclub\GenreScrapper::class, function () {
            return new Scrappers\Bookclub\GenreScrapper($this->app->make(Parsers\Bookclub\GenreParser::class));
        });

        $this->app->bindMethod([ScrapeAuthorBySlugJob::class, 'handle'], function ($job, $app) {
            return $job->handle(
                $app->make(FindSlugable::class),
                $app->make(CreateSlugable::class),
                $app->make(Scrappers\Bookclub\AuthorScrapper::class),
            );
        });

        $this->app->bindMethod([ScrapeGenreBySlugJob::class, 'handle'], function ($job, $app) {
            return $job->handle(
                $app->make(FindSlugable::class),
                $app->make(CreateSlugable::class),
                $app->make(Scrappers\Bookclub\GenreScrapper::class),
            );
        });

        $this->app->bindMethod([ScrapeBookBySlugJob::class, 'handle'], function ($job, $app) {
            return $job->handle(
                $app->make(FindSlugable::class),
                $app->make(CreateSlugable::class),
                $app->make(Scrappers\Bookclub\BookScrapper::class),
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
