<?php

namespace App\Providers;

use App\Scrapping\Source;
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
        $this->app->bind(Source::Bookclub . '-book-scrapper', \App\Scrapping\Scrappers\Bookclub\BookScrapper::class);
        $this->app->bind(Source::Bookclub . '-genre-scrapper', \App\Scrapping\Scrappers\Bookclub\GenreScrapper::class);
        $this->app->bind(Source::Bookclub . '-author-scrapper', \App\Scrapping\Scrappers\Bookclub\AuthorScrapper::class);

        $this->app->when(\App\Scrapping\Scrappers\Bookclub\BookScrapper::class)
            ->needs(\App\Scrapping\Parser::class)
            ->give(function () {
                return new \App\Scrapping\Parsers\Bookclub\BookParser();
            });

        $this->app->when(\App\Scrapping\Scrappers\Bookclub\GenreScrapper::class)
            ->needs(\App\Scrapping\Parser::class)
            ->give(function () {
                return new \App\Scrapping\Parsers\Bookclub\GenreParser();
            });

        $this->app->when(\App\Scrapping\Scrappers\Bookclub\AuthorScrapper::class)
            ->needs(\App\Scrapping\Parser::class)
            ->give(function () {
                return new \App\Scrapping\Parsers\Bookclub\AuthorParser();
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
