<?php

namespace App\Providers;

use App\Scrapping\Parser;
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
