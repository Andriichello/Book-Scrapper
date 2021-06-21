<?php

namespace App\Providers;

use App\Scrapping\Parser;
use App\Scrapping\Scrappers\Bookclub\AuthorScrapper;
use App\Scrapping\Scrappers\Bookclub\BookScrapper;
use App\Scrapping\Scrappers\Bookclub\GenreScrapper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('bookclub-book-scrapper', BookScrapper::class);
        $this->app->bind('bookclub-genre-scrapper', GenreScrapper::class);
        $this->app->bind('bookclub-author-scrapper', AuthorScrapper::class);

        $this->app->when(\App\Scrapping\Scrappers\Bookclub\AuthorScrapper::class)
            ->needs(Parser::class)
            ->give(function () {
                return new \App\Scrapping\Parsers\Bookclub\AuthorParser();
            });

        $this->app->when(\App\Scrapping\Scrappers\Bookclub\GenreScrapper::class)
            ->needs(Parser::class)
            ->give(function () {
                return new \App\Scrapping\Parsers\Bookclub\GenreParser();
            });

        $this->app->when(\App\Scrapping\Scrappers\Bookclub\BookScrapper::class)
            ->needs(Parser::class)
            ->give(function () {
                return new \App\Scrapping\Parsers\Bookclub\BookParser();
            });
    }
}
