<?php

namespace App\Console;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Slug;
use App\Services\Scrapping\Source;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $slugableType = array_search(Author::class, Relation::$morphMap, true);

            $authorSlugs = Slug::query()
                ->where('source', Source::Bookclub)
                ->where('slugable_type', $slugableType)
                ->get();

            foreach ($authorSlugs as $authorSlug) {
                Artisan::call('scrape:bookclub-author', ['slug' => $authorSlug->slug]);
            }
        })->daily()
            ->name('Updating authors by bookclub-slug')
            ->withoutOverlapping();

        $schedule->call(function () {
            $slugableType = array_search(Genre::class, Relation::$morphMap, true);

            $genreSlugs = Slug::query()
                ->where('source', Source::Bookclub)
                ->where('slugable_type', $slugableType)
                ->get();

            foreach ($genreSlugs as $genreSlug) {
                Artisan::call('scrape:bookclub-genre', ['slug' => $genreSlug->slug]);
            }
        })->daily()
            ->name('Updating genres by bookclub-slug')
            ->withoutOverlapping();

        $schedule->call(function () {
            $slugableType = array_search(Book::class, Relation::$morphMap, true);

            $bookSlugs = Slug::query()
                ->where('source', Source::Bookclub)
                ->where('slugable_type', $slugableType)
                ->get();

            foreach ($bookSlugs as $bookSlug) {
                Artisan::call('scrape:bookclub-book', ['slug' => $bookSlug->slug]);
            }
        })->daily()
            ->name('Updating books by bookclub-slug')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
