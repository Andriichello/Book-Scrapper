<?php

namespace App\Jobs\Bookclub;

use App\Jobs\ScrapeFromSourceBySlugJob;
use App\Models\Author;
use App\Models\Image;
use App\Models\Traits\Imageable;
use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Scrapping\Scrapper;
use App\Services\Scrapping\Scrappers\Bookclub\AuthorScrapper;
use App\Services\Scrapping\Source;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ScrapeAuthorBySlugJob extends ScrapeFromSourceBySlugJob
{
    protected string $model = Author::class;

    protected string $source = Source::Bookclub;

    public function handle(FindSlugable $find, CreateSlugable $create, Scrapper $scrapper): void
    {
        parent::handle($find, $create, $scrapper);

        $this->storeImage();
    }

    protected function storeImage(): void {
        $imageUrl = data_get($this->scrappedData, 'image');
        if (empty($imageUrl) || empty($this->scrappedObj)) {
            return;
        }

        if ($this->scrappedObj->images()->where('url', '=', $imageUrl)->exists()) {
            Log::debug('Author\'s image was already saved: ' . $imageUrl);
            return;
        }

        if ($this->scrappedObj->images()->save(new Image(['url' => $imageUrl]))) {
            Log::info('Successfully saved author\'s image: ' . $imageUrl);
        }
    }
}
