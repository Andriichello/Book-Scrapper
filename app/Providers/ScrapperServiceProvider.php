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
            return new Scrappers\Bookclub\BookScrapper($this->app->make(Parsers\Bookclub\BookParser::class));
        });

        $this->app->bind(Scrappers\Bookclub\AuthorScrapper::class, function () {
            return new Scrappers\Bookclub\AuthorScrapper($this->app->make(Parsers\Bookclub\AuthorParser::class));
        });

        $this->app->bind(Scrappers\Bookclub\GenreScrapper::class, function () {
            return new Scrappers\Bookclub\GenreScrapper($this->app->make(Parsers\Bookclub\GenreParser::class));
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
