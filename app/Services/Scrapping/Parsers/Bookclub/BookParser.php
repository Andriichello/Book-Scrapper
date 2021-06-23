<?php

namespace App\Services\Scrapping\Parsers\Bookclub;

use App\Services\Scrapping\Parser;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class BookParser extends Parser
{
    protected array $characteristicNames = [
        'Код товару' => 'id',
        'Назва товару' => 'title',
        'Оригінальна назва' => 'original_title',
        'Автор' => 'author',
        'Aвтор' => 'author',
        'Мова' => 'language',
        'Мова оригіналу' => 'original_language',
        'Обкладинка' => 'cover',
        'Формат' => 'format',
        'Видавництво' => 'publisher',
        'ISBN' => 'isbn',
        'Вікові обмеження' => 'age_restriction',
        'Розділ:' => 'genre',
        'Рік видання' => 'year',
        'Сторінок' => 'pages',
        'Ілюстрації' => 'details.illustrations',
        'Вага' => 'details.weight',
        'Серія' => 'details.series',
        'Перекладач(і)' => 'details.translators',
    ];

    protected function parseImage(Crawler $crawler): ?string
    {
        $imageCrawler = $crawler->filter('.prd-image .imgprod')->first();
        return $this->parseElementAttribute($imageCrawler, 'src');
    }

    protected function parseTitle(Crawler $crawler): ?string
    {
        $imageCrawler = $crawler->filter('.prd-image a img')->first();
        return $this->parseElementAttribute($imageCrawler, 'alt');
    }

    protected function parseDescription(Crawler $crawler): ?string
    {
        $descriptionCrawler = $crawler->filter('.prd-descr-all .proddesc')->first();
        return $this->isEmpty($descriptionCrawler) ? null : htmlentities($descriptionCrawler->html());
    }

    protected function parsePrices(Crawler $crawler): ?array
    {
        $priceNode = $crawler->filter('.prd-your-price-numb')->first();
        $clubPriceNode = $crawler->filter('.prd-enov-pr-numb')->first();
        $currencyNode = $priceNode->filter('.prd-your-price-valute')->first();

        $currency = $this->isEmpty($currencyNode) ? '' : $currencyNode->text();
        $price = $this->isEmpty($priceNode) ? null : rtrim($priceNode->text(), $currency);
        $clubPrice = $this->isEmpty($clubPriceNode) ? null : rtrim($clubPriceNode->text(), $currency);

        return [
            'currency' => empty($currency) ? null : preg_replace('/\s+/u', '', $currency),
            'price' => $price,
            'club_price' => $clubPrice,
        ];
    }

    protected function parseAuthor(Crawler $crawler): ?array
    {
        $referenceCrawler = $crawler->filter('.prd-abt-author-img a')->first();
        if ($this->isEmpty($referenceCrawler)) {
            return null;
        }

        $imageCrawler = $referenceCrawler->filter('img')->first();

        return [
            'slug' => Str::of($this->parseElementAttribute($referenceCrawler, 'href', ''))
                ->trim('/')->explode('/')->last(),
            'name' => $this->parseElementAttribute($imageCrawler, 'alt'),
            'image' => $this->parseElementAttribute($imageCrawler, 'src'),
        ];
    }

    protected function parseCharacteristic(Crawler $crawler): ?array
    {
        $characteristicName = $crawler->filter('.prd-attr-name')->first();
        $characteristicValue = $crawler->filter('.prd-attr-descr')->first();

        if ($this->isEmpty($characteristicName) || $this->isEmpty($characteristicValue)) {
            return null;
        }

        $name = Arr::get($this->characteristicNames, $characteristicName->text(), $characteristicName->text());

        $characteristicReference = $characteristicValue->filter('a')->first();
        return [$name, $characteristicValue->text(), $this->parseElementAttribute($characteristicReference, 'href')];
    }

    protected function parseCharacteristics(Crawler $crawler): ?array
    {
        $characteristicsCrawler = $crawler->filter('.prd-attributes .prodchap');
        if ($this->isEmpty($characteristicsCrawler)) {
            return null;
        }

        $hrefs = [];
        $attributes = [];
        $characteristicsCrawler->each(function ($characteristicCrawler) use (&$hrefs, &$attributes) {
            [$name, $value, $href] = $this->parseCharacteristic($characteristicCrawler);
            Arr::set($attributes, $name, $value);

            if (isset($href)) {
                Arr::set($hrefs, $name, $href);
            }
        });

        return [$attributes, $hrefs];
    }

    protected function parseAdditional(Crawler $crawler): ?array
    {
        $characteristics = $this->parseCharacteristics($crawler);
        if (empty($characteristics)) {
            return null;
        }

        $author = $this->parseAuthor($crawler);
        if (!empty($author)) {
            $characteristics[0]['author'] = $author;
        }

        if (isset($characteristics[1]['genre'])) {
            $characteristics[0]['genre'] = [
                'slug' => Str::of($characteristics[1]['genre'])->trim('/')->explode('/')->last(),
                'name' => data_get($characteristics[0], 'genre'),
            ];
        }
        return $characteristics[0];
    }

    protected function parseData(Crawler $crawler, array $params = []): ?array
    {
        $data = array_merge(
            [
                'slug' => data_get($params, 'slug'),
                'title' => $this->parseTitle($crawler),
                'image' => $this->parseImage($crawler),
                'description' => $this->parseDescription($crawler),
            ],
            $this->parsePrices($crawler) ?? [],
            $this->parseAdditional($crawler) ?? [],
        );

        return $this->validatedData($data);
    }

    protected function validatedData(array $data): ?array
    {
        // todo: add validation rules
        if (empty($data['title'])) {
            return null;
        }

        return $data;
    }
}
