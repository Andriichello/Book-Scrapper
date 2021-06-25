<?php

namespace App\Providers;

use App\Console\Commands\Bookclub\ScrapeAuthorBySlug;
use App\Console\Commands\Bookclub\ScrapeBookBySlug;
use App\Console\Commands\Bookclub\ScrapeGenreBySlug;
use App\Services\Scrapping\Scrapper;
use App\Services\Scrapping\Source;
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

        $this->app->when(ScrapeAuthorBySlug::class)
            ->needs(Scrapper::class)
            ->give(Scrappers\Bookclub\AuthorScrapper::class);

        $this->app->when(ScrapeGenreBySlug::class)
            ->needs(Scrapper::class)
            ->give(Scrappers\Bookclub\GenreScrapper::class);

        $this->app->when(ScrapeBookBySlug::class)
            ->needs(Scrapper::class)
            ->give(Scrappers\Bookclub\BookScrapper::class);
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
