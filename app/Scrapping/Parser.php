<?php

namespace App\Scrapping;

use Symfony\Component\DomCrawler\Crawler;

abstract class Parser
{
    public function parse(Crawler $crawler, array $params = []): ?array
    {
        if ($crawler === null) {
            return null;
        }

        return [];
    }

    protected function isEmpty(?Crawler $crawler): bool
    {
        return empty($crawler) || $crawler->count() === 0;
    }

    public function parseElementAttribute(Crawler $crawler, string $attributeName, mixed $default = null): mixed
    {
        if ($this->isEmpty($crawler)) {
            return $default;
        }

        return $crawler->attr($attributeName) ?? $default;
    }
}
