<?php

namespace App\Console\Commands;

use App\Console\Commands\Slugable;
use App\Console\Commands\Sourcable;
use App\Models\Author;
use App\Services\Actions\CreateSlugable;
use App\Services\Actions\FindSlugable;
use App\Services\Actions\UpdateSlugable;
use App\Services\Scrapping\Scrapper;
use App\Services\Scrapping\Scrappers\Bookclub\AuthorScrapper;
use App\Services\Scrapping\Source;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

abstract class ScrapeFromSourceBySlug extends Command
{
    use Slugable;

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

    protected FindSlugable $find;
    protected CreateSlugable $create;
    protected Scrapper $scrapper;

    public function __construct(FindSlugable $find, CreateSlugable $create, Scrapper $scrapper)
    {
        parent::__construct();

        $this->find = $find;
        $this->create = $create;
        $this->scrapper = $scrapper;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle(): int
    {
        $instance = $this->createOrUpdateModel($this->scrape());
        $this->displayModel($instance);

        return isset($instance) ? 1 : 0;
    }

    protected function scrape(): array
    {
        $data = $this->scrapper->scrape(['slug' => $this->argument('slug')]);
        if (empty($data)) {
            throw new \Exception("Unable to scrape data by given slug ({$this->argument('slug')})");
        }

        return $data;
    }

    protected function findModel(): ?Model
    {
        return $this->findSlugableModel($this->model, $this->find, $this->argument('slug'), $this->source);
    }

    protected function createModel(array $data): ?Model
    {
        return $this->createSlugableModel($this->model, $data, $this->create, $this->argument('slug'), $this->source);
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
            $this->info($updated ? 'Successfully updated.' : 'Failed to update.');

            return $updated ? $instance : null;
        }

        $instance = $this->createModel($data);
        $this->info(isset($instance) ? 'Successfully created.' : 'Failed to create.');
        return $instance;
    }

    protected function displayModel(Model $model): void
    {
        $this->line('model: ' . json_encode($model, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
