<?php

namespace App\Services;

use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\IpUtils;

class JobPageFetcher
{
    public function __construct(private PublicDnsResolver $dns) {}

    public function get(string $url): Response
    {
        if (! extension_loaded('curl')) {
            throw new \RuntimeException('Secure job fetching requires the cURL extension.');
        }
        for ($hop = 0; $hop <= (int) config('job_scraping.max_redirects', 3); $hop++) {
            [$host, $port, $ip] = $this->destination($url);
            $pinnedIp = str_contains($ip, ':') ? '['.$ip.']' : $ip;
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9,id;q=0.8',
                'Accept' => 'text/html,application/xhtml+xml',
            ])->setHandler(new CurlHandler)->connectTimeout(5)->timeout(12)->withOptions([
                'allow_redirects' => false,
                'proxy' => '',
                'verify' => true,
                'curl' => [
                    CURLOPT_RESOLVE => ["{$host}:{$port}:{$pinnedIp}"],
                    CURLOPT_PROXY => '',
                    CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                    CURLOPT_FOLLOWLOCATION => false,
                    // Never reuse a connection that predates this DNS validation.
                    CURLOPT_FRESH_CONNECT => true,
                    CURLOPT_FORBID_REUSE => true,
                ],
            ])->get($url);
            if (! in_array($response->status(), [301, 302, 303, 307, 308], true)) {
                return $response;
            }
            $location = $response->header('Location');
            if (! $location || preg_match('/[\x00-\x20\x7f\\\\]/', $location)) {
                $this->reject();
            }
            $url = (string) UriResolver::resolve(new Uri($url), new Uri($location));
        }
        $this->reject('Terlalu banyak pengalihan pada URL lowongan.');
    }

    public function validateUrl(string $url): void
    {
        $this->destination($url);
    }

    private function destination(string $url): array
    {
        if (strlen($url) > 2048 || preg_match('/[\x00-\x20\x7f\\\\]/', $url)) {
            $this->reject();
        }
        $parts = parse_url($url);
        $scheme = strtolower($parts['scheme'] ?? '');
        $host = strtolower($parts['host'] ?? '');
        if (! in_array($scheme, ['http', 'https'], true) || isset($parts['user']) || isset($parts['pass'])
            || ! preg_match('/\A[a-z0-9]+(?:[.-][a-z0-9]+)*\z/D', $host)) {
            $this->reject();
        }
        $port = $parts['port'] ?? ($scheme === 'https' ? 443 : 80);
        if ($port !== ($scheme === 'https' ? 443 : 80)) {
            $this->reject();
        }
        $allowed = false;
        foreach (config('job_scraping.allowed_domains', []) as $domain) {
            if ($host === $domain || str_ends_with($host, '.'.$domain)) {
                $allowed = true;
                break;
            }
        }
        if (! $allowed) {
            $this->reject('URL harus berasal dari portal lowongan yang didukung.');
        }
        $addresses = $this->dns->resolve($host);
        if ($addresses === []) {
            $this->reject('Domain lowongan tidak dapat diresolusikan.');
        }
        foreach ($addresses as $ip) {
            if (! $this->isPublic($ip)) {
                $this->reject();
            }
        }

        return [$host, $port, $addresses[0]];
    }

    private function isPublic(string $ip): bool
    {
        if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return false;
        }
        if (str_contains($ip, ':')) {
            return IpUtils::checkIp($ip, '2000::/3')
                && ! IpUtils::checkIp($ip, ['2001::/23', '2001:db8::/32', '2002::/16', '3fff::/20']);
        }

        return ! IpUtils::checkIp($ip, [
            '0.0.0.0/8', '100.64.0.0/10', '127.0.0.0/8', '169.254.0.0/16',
            '192.0.0.0/24', '192.0.2.0/24', '192.88.99.0/24',
            '198.18.0.0/15', '198.51.100.0/24', '203.0.113.0/24',
            '224.0.0.0/4', '240.0.0.0/4',
        ]);
    }

    private function reject(string $message = 'Tujuan URL lowongan tidak diizinkan.'): never
    {
        throw ValidationException::withMessages(['url' => $message]);
    }
}
