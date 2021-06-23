<?php

namespace App\Providers;

use App\Scrapping\Source;
use App\Scrapping\Parsers;
use App\Scrapping\Scrappers;
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
            new Scrappers\Bookclub\BookScrapper($this->app->make(Parsers\Bookclub\BookParser::class));
        });

        $this->app->bind(Scrappers\Bookclub\AuthorScrapper::class, function () {
            new Scrappers\Bookclub\AuthorScrapper($this->app->make(Parsers\Bookclub\AuthorParser::class));
        });

        $this->app->bind(Scrappers\Bookclub\GenreScrapper::class, function () {
            new Scrappers\Bookclub\GenreScrapper($this->app->make(Parsers\Bookclub\GenreParser::class));
        });

        $this->app->bind(Source::Bookclub . '-book-scrapper', function () {
            return $this->app->make(Scrappers\Bookclub\BookScrapper::class);
        });

        $this->app->bind(Source::Bookclub . '-genre-scrapper', function () {
            return $this->app->make(Scrappers\Bookclub\GenreScrapper::class);
        });

        $this->app->bind(Source::Bookclub . '-author-scrapper', function () {
            return $this->app->make(Scrappers\Bookclub\GenreScrapper::class);
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
