<?php

namespace App\Jobs;

use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Scrapping\Scrapper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

abstract class ScrapeFromSourceBySlugJob  implements ShouldQueue
{
    use Slugable;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The name of the model to be scrapped.
     *
     * @var string
     */
    protected string $model = '';

    /**
     * The name of the scrapping source.
     *
     * @var string
     */
    protected string $source = '';

    protected string $slug;
    protected FindSlugable $find;
    protected CreateSlugable $create;
    protected Scrapper $scrapper;

    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle(FindSlugable $find, CreateSlugable $create, Scrapper $scrapper): void
    {
        $this->find = $find;
        $this->create = $create;
        $this->scrapper = $scrapper;

        $instance = $this->createOrUpdateModel($this->scrape());
        $this->displayModel($instance);

        if (empty($instance)) {
            $this->fail();
        }
    }

    protected function scrape(): array
    {
        $data = $this->scrapper->scrape(['slug' => $this->slug]);
        if (empty($data)) {
            throw new \Exception("Unable to scrape data by given slug ({$this->slug})");
        }

        return $data;
    }

    protected function findModel(): ?Model
    {
        return $this->findSlugableModel($this->model, $this->find, $this->slug, $this->source);
    }

    protected function createModel(array $data): ?Model
    {
        return $this->createSlugableModel($this->model, $data, $this->create, $this->slug, $this->source);
    }

    protected function updateModel(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    protected function createOrUpdateModel(array $data): ?Model
    {
        $instance = $this->findModel();
        if (isset($instance)) {
            $updated = $this->updateModel($instance, $data);

            Log::info($updated ? 'Successfully updated.' : 'Failed to update.');

            return $updated ? $instance : null;
        }

        $instance = $this->createModel($data);
        Log::info(isset($instance) ? 'Successfully created.' : 'Failed to create.');
        return $instance;
    }

    protected function displayModel(Model $model): void
    {
        Log::debug('model: ' . json_encode($model, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
