<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
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
        Relation::morphMap([
            'slugs' => 'App\Models\Slug',
            'books' => 'App\Models\Book',
            'genres' => 'App\Models\Genre',
            'authors' => 'App\Models\Author',
            'publishers' => 'App\Models\Publisher',
        ]);
    }
}
