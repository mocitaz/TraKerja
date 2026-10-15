<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ScraperPageFetcher
{
    public function __construct(private JobPageFetcher $pages) {}

    public function fetch(string $url): array
    {
        $this->pages->validateUrl($url);
        $service = config('scraper.microservice_url');
        $token = config('scraper.service_token');

        if (is_string($service) && $service !== '' && is_string($token) && $token !== '') {
            try {
                $response = Http::withToken($token)->connectTimeout(5)->timeout(45)
                    ->withOptions(['allow_redirects' => false, 'proxy' => ''])
                    ->post(rtrim($service, '/').'/scrape', ['url' => $url]);
                $payload = $response->json();
                if ($response->successful() && is_array($payload)
                    && ($payload['success'] ?? null) === true
                    && is_string($payload['html'] ?? null) && $payload['html'] !== ''
                    && is_string($payload['title'] ?? null)) {
                    return ['html' => $payload['html'], 'title' => $payload['title']];
                }
            } catch (\Exception) {
                // Renderer failures must use the same validated transport as direct fetches.
            }
        }

        $response = $this->pages->get($url);
        if (! $response->successful() || $response->body() === '') {
            throw new \RuntimeException('Failed to fetch job page.');
        }

        return ['html' => $response->body(), 'title' => null];
    }
}
