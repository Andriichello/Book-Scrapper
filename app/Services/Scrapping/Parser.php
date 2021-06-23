<?php

namespace App\Services\Scrapping;

use Symfony\Component\DomCrawler\Crawler;

abstract class Parser
{
    protected function isEmpty(?Crawler $crawler): bool
    {
        return empty($crawler) || $crawler->count() === 0;
    }

    protected function parseData(Crawler $crawler, array $params = []): ?array
    {
        return $this->validatedData([]);
    }

    protected function validatedData(array $data): ?array
    {
        return $data;
    }

    public function parse(Crawler $crawler, array $params = []): ?array
    {
        if ($this->isEmpty($crawler)) {
            return null;
        }

        return $this->parseData($crawler, $params);
    }

    public function parseElementAttribute(Crawler $crawler, string $attributeName, mixed $default = null): mixed
    {
        if ($this->isEmpty($crawler)) {
            return $default;
        }

        return $crawler->attr($attributeName) ?? $default;
    }
}
