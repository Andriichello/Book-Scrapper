<?php

namespace App\Scrapping;

use Goutte\Client;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\DomCrawler\Crawler;

abstract class Scrapper
{
    protected string $url;
    protected string $method;

    protected ?Client $client = null;
    protected ?Crawler $crawler = null;

    public function __construct(string $url, string $method = 'GET')
    {
        $this->url = $url;
        $this->method = strtoupper($method);

        $this->loadClient();
    }

    protected function getUrl(array $params = []): string
    {
        if (empty($params)) {
            return $this->url;
        }

        $url = $this->url;
        // apply specified params
        return $url;
    }

    protected function getMethod(): string
    {
        return $this->method;
    }

    protected function loadClient(): ?Client
    {
        return $this->client = new Client();
    }

    protected function loadCrawler(string $url, string $method): ?Crawler
    {
        if (empty($this->client)) {
            return null;
        }

        return $this->crawler = $this->client->request($method, $url);
    }

    public function scrape(Parser $parser, array $params = []): ?array
    {
        if ($parser == null) {
            return null;
        }

        return $parser->parse(
            $this->loadCrawler(
                $this->getUrl($params),
                $this->getMethod()
            )
        );
    }
}
