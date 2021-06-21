<?php

namespace App\Scrapping;

use Goutte\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class Scrapper
{
    protected string $url;
    protected string $method;

    protected ?Parser $parser = null;
    protected ?Client $client = null;
    protected ?Crawler $crawler = null;
    protected ?LoggerInterface $logger = null;

    public function __construct(string $url, string $method, Parser $parser, ?LoggerInterface $logger = null)
    {
        $this->parser = $parser;
        $this->logger = $logger;

        $this->url = $url;
        $this->method = strtoupper($method);

        $this->loadClient();
    }

    public function getUrl(array $params = []): string
    {
        if (empty($params)) {
            return $this->url;
        }

        $url = $this->url;
        // apply specified params
        return $url;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParser(): ?Parser
    {
        return $this->parser;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
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

    public function scrape(array $params = []): ?array
    {
        if ($this->parser == null) {
            return null;
        }

        return $this->parser->parse(
            $this->loadCrawler(
                $this->getUrl($params),
                $this->getMethod()
            ),
            $params
        );
    }
}
