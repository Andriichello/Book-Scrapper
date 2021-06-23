<?php

namespace App\Scrapping;

use Goutte\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class Scrapper
{
    protected string $url;
    protected string $method;

    protected Parser $parser;
    protected Client $client;
    protected ?Crawler $crawler = null;
    protected ?LoggerInterface $logger = null;

    public function __construct(string $url, string $method, Parser $parser, ?LoggerInterface $logger = null)
    {
        $this->url = $url;
        $this->method = strtoupper($method);

        $this->parser = $parser;
        $this->logger = $logger;

        $this->renewClient();
    }

    public function getUrl(array $params = []): string
    {
        return $this->url;
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

    protected function renewClient(): Client
    {
        return $this->client = new Client();
    }

    protected function currentClient(): Client
    {
        return $this->client;
    }

    protected function renewRequest(string $url, string $method): ?Crawler
    {
        return $this->crawler = $this->client->request($method, $url);
    }

    protected function currentRequest(): ?Crawler
    {
        return $this->crawler;
    }

    public function scrape(array $params = []): ?array
    {
        $url = $this->getUrl($params);
        $method = $this->getMethod();

        $crawler = $this->renewRequest($url, $method);
        return $this->parser->parse($crawler, $params);
    }
}
