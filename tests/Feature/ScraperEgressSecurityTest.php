<?php

namespace Tests\Feature;

use App\Jobs\ScrapeJobDetailsJob;
use App\Models\JobPosting;
use App\Models\ScraperSource;
use App\Services\DeadLinkDetector;
use App\Services\PublicDnsResolver;
use App\Services\ScraperPageFetcher;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ScraperEgressSecurityTest extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => str_repeat('s', 32), 'cache.default' => 'array',
            'database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'logging.default' => 'null', 'scraper.microservice_url' => 'http://renderer.test',
            'scraper.service_token' => str_repeat('t', 32),
            'scraper.proxies' => ['http://unsafe-proxy.test']]);
        $this->resolveTo(['93.184.216.34']);
        Http::preventStrayRequests();
    }

    private function resolveTo(array $addresses): void
    {
        $resolver = \Mockery::mock(PublicDnsResolver::class);
        $resolver->shouldReceive('resolve')->andReturn($addresses);
        $this->app->instance(PublicDnsResolver::class, $resolver);
    }

    public function test_discovery_rejects_private_seed_before_any_http_request(): void
    {
        Http::fake(['*' => Http::response(['success' => true, 'html' => '<a href="https://linkedin.com/jobs/view/1">job</a>', 'title' => 'Jobs'])]);
        $source = new ScraperSource(['target_domain' => 'linkedin.com', 'seed_url' => 'http://127.0.0.1/admin']);
        $this->assertSame([], $source->executeDiscovery());
        Http::assertNothingSent();
    }

    public function test_discovery_filters_by_parsed_host_and_resolves_relative_job_links(): void
    {
        $html = '<a href="https://linkedin.com.attacker.test/jobs/view/1">bad</a>'
            .'<a href="https://attacker.test/jobs/view/2?linkedin.com">bad</a>'
            .'<a href="https://glints.com/jobs/view/3">other portal</a>'
            .'<a href="//127.0.0.1/jobs/view/4">private</a>'
            .'<a href="https://user:pass@linkedin.com/jobs/view/5">credentials</a>'
            .'<a href="/jobs/view/6">root relative</a>'
            ."<a href='view/7'>relative</a>"
            .'<a href="https://id.linkedin.com/jobs/view/8">valid subdomain</a>';
        Http::fake(['*' => Http::response(['success' => true, 'html' => $html, 'title' => 'Jobs'])]);
        $source = new ScraperSource(['target_domain' => 'linkedin.com', 'seed_url' => 'https://linkedin.com/jobs/search']);
        $this->assertSame(['https://linkedin.com/jobs/view/6', 'https://linkedin.com/jobs/view/7',
            'https://id.linkedin.com/jobs/view/8'], $source->executeDiscovery());
    }

    public static function supportedDiscoveryCases(): array
    {
        return [
            ['jobstreet.co.id', '<a href="/job/1">job</a><a href="/jobs/2">job</a><a href="/about">no</a>',
                ['https://jobstreet.co.id/job/1', 'https://jobstreet.co.id/jobs/2']],
            ['kalibrr.com', '<a href="/c/example/jobs/1">job</a><a href="/jobs/2">no</a>',
                ['https://kalibrr.com/c/example/jobs/1']],
            ['glints.com', '<a href="/opportunities/jobs/1">job</a><a href="https://glints.com.evil.test/">no</a>',
                ['https://glints.com/opportunities/jobs/1']],
        ];
    }

    #[DataProvider('supportedDiscoveryCases')]
    public function test_supported_discovery_paths_are_preserved(string $domain, string $html, array $expected): void
    {
        Http::fake(['*' => Http::response(['success' => true, 'html' => $html, 'title' => 'Jobs'])]);
        $source = new ScraperSource(['target_domain' => $domain, 'seed_url' => 'https://'.$domain.'/search']);
        $this->assertSame($expected, $source->executeDiscovery());
    }

    public function test_discovery_rejects_subdomain_with_private_dns_and_keeps_ten_unique_links(): void
    {
        $resolver = \Mockery::mock(PublicDnsResolver::class);
        $resolver->shouldReceive('resolve')->with('linkedin.com')->times(11)->andReturn(['93.184.216.34']);
        $resolver->shouldReceive('resolve')->with('private.linkedin.com')->once()->andReturn(['10.0.0.1']);
        $this->app->instance(PublicDnsResolver::class, $resolver);
        $html = '<a href="https://private.linkedin.com/jobs/view/99">no</a>';
        for ($i = 0; $i < 12; $i++) {
            $html .= '<a href="/jobs/view/'.$i.'">job</a><a href="/jobs/view/'.$i.'">duplicate</a>';
        }
        Http::fake(['*' => Http::response(['success' => true, 'html' => $html, 'title' => 'Jobs'])]);
        $source = new ScraperSource(['target_domain' => 'linkedin.com', 'seed_url' => 'https://linkedin.com/search']);
        $this->assertSame(['https://linkedin.com/jobs/view/0', 'https://linkedin.com/jobs/view/1',
            'https://linkedin.com/jobs/view/2', 'https://linkedin.com/jobs/view/3',
            'https://linkedin.com/jobs/view/4', 'https://linkedin.com/jobs/view/5',
            'https://linkedin.com/jobs/view/6', 'https://linkedin.com/jobs/view/7',
            'https://linkedin.com/jobs/view/8', 'https://linkedin.com/jobs/view/9'], $source->executeDiscovery());
    }

    public function test_microservice_is_authenticated_and_receives_only_validated_url(): void
    {
        Http::fake(['*' => Http::response(['success' => true, 'html' => '<h1>Engineer</h1>', 'title' => 'Engineer'])]);
        $page = $this->app->make(ScraperPageFetcher::class)->fetch('https://linkedin.com/jobs/view/42');
        $this->assertSame('<h1>Engineer</h1>', $page['html']);
        $this->assertSame('Engineer', $page['title']);
        Http::assertSent(fn ($request) => $request->url() === 'http://renderer.test/scrape'
            && $request->hasHeader('Authorization', 'Bearer '.str_repeat('t', 32))
            && $request->data() === ['url' => 'https://linkedin.com/jobs/view/42']);
        Http::assertSentCount(1);
    }

    public function test_nonpublic_dns_prevents_microservice_dispatch(): void
    {
        $this->resolveTo(['93.184.216.34', '169.254.169.254']);
        Http::fake();
        try {
            $this->app->make(ScraperPageFetcher::class)->fetch('https://linkedin.com/jobs/view/42');
            $this->fail('Nonpublic DNS must be rejected');
        } catch (ValidationException) {
            Http::assertNothingSent();
        }
    }

    public static function fallbackCases(): array
    {
        return [[null, str_repeat('t', 32), null], ['http://renderer.test', '', null],
            ['http://renderer.test', str_repeat('t', 32), ['success' => true, 'html' => [], 'title' => 'Bad']],
            ['http://renderer.test', str_repeat('t', 32), ['success' => 'true', 'html' => 'bad', 'title' => 'Bad']],
            ['http://renderer.test', str_repeat('t', 32), ['success' => true, 'html' => 'bad', 'title' => []]],
            ['http://renderer.test', str_repeat('t', 32), ['success' => false]]];
    }

    #[DataProvider('fallbackCases')]
    public function test_missing_service_configuration_and_invalid_responses_use_pinned_proxy_free_fallback(?string $service, string $token, ?array $payload): void
    {
        config(['scraper.microservice_url' => $service, 'scraper.service_token' => $token]);
        Http::fake(['http://renderer.test/scrape' => Http::response($payload ?? [], 200),
            'https://linkedin.com/jobs/view/42' => Http::response('<h1>Fallback</h1>')]);
        $observed = false;
        Http::globalMiddleware(function ($handler) use (&$observed) {
            return function ($request, $options) use ($handler, &$observed) {
                if ($request->getMethod() === 'GET') {
                    $observed = true;
                    $this->assertFalse($options['allow_redirects']);
                    $this->assertSame('', $options['proxy']);
                    $this->assertSame(['linkedin.com:443:93.184.216.34'], $options['curl'][CURLOPT_RESOLVE]);
                    $this->assertTrue($options['verify']);
                }

                return $handler($request, $options);
            };
        });
        $page = $this->app->make(ScraperPageFetcher::class)->fetch('https://linkedin.com/jobs/view/42');
        $this->assertSame('<h1>Fallback</h1>', $page['html']);
        $this->assertTrue($observed);
        Http::assertSentCount($service && $token ? 2 : 1);
    }

    public function test_renderer_failure_cannot_follow_private_redirect_in_fallback(): void
    {
        Http::fake(['http://renderer.test/scrape' => Http::failedConnection(),
            'https://linkedin.com/jobs/view/42' => Http::response('', 302, ['Location' => 'http://127.0.0.1/private'])]);
        try {
            $this->app->make(ScraperPageFetcher::class)->fetch('https://linkedin.com/jobs/view/42');
            $this->fail('Private redirect must be rejected');
        } catch (ValidationException) {
            Http::assertSentCount(2);
        }
    }

    public function test_fallback_revalidates_relative_redirects_and_pins_each_request(): void
    {
        config(['scraper.service_token' => null]);
        Http::fake(['https://linkedin.com/jobs' => Http::response('', 302, ['Location' => '/jobs/view/42']),
            'https://linkedin.com/jobs/view/42' => Http::response('<h1>Engineer</h1>')]);
        $page = $this->app->make(ScraperPageFetcher::class)->fetch('https://linkedin.com/jobs');
        $this->assertSame('<h1>Engineer</h1>', $page['html']);
        Http::assertSentCount(2);
    }

    public function test_fallback_rechecks_dns_after_renderer_failure(): void
    {
        $resolver = \Mockery::mock(PublicDnsResolver::class);
        $resolver->shouldReceive('resolve')->with('linkedin.com')->andReturn(['93.184.216.34'], ['127.0.0.1']);
        $this->app->instance(PublicDnsResolver::class, $resolver);
        Http::fake(['http://renderer.test/scrape' => Http::response([], 500)]);
        try {
            $this->app->make(ScraperPageFetcher::class)->fetch('https://linkedin.com/jobs');
            $this->fail('DNS rebinding must be rejected');
        } catch (ValidationException) {
            Http::assertSentCount(1);
        }
    }

    public function test_dead_link_check_rejects_private_urls_without_http(): void
    {
        Http::fake(['*' => Http::response('active')]);
        $posting = new JobPosting(['raw_url' => 'http://169.254.169.254/latest/meta-data']);
        $this->assertSame('escalate', (new DeadLinkDetector)->validate($posting));
        Http::assertNothingSent();
    }

    public static function deadLinkCases(): array
    {
        return [[404, '', 'closed'], [200, 'This listing is closed', 'closed'],
            [200, 'Apply here', 'active'], [403, '', 'escalate']];
    }

    #[DataProvider('deadLinkCases')]
    public function test_dead_link_check_preserves_closed_active_and_blocked_results(int $status, string $body, string $expected): void
    {
        $posting = new JobPosting(['raw_url' => 'https://linkedin.com/jobs/view/42']);
        Http::fake(['*' => Http::response($body, $status)]);
        $this->assertSame($expected, (new DeadLinkDetector)->validate($posting));
        Http::assertSentCount(1);
    }

    public function test_extraction_rejects_unsafe_url_without_http_or_persistence(): void
    {
        Http::fake(['*' => Http::response(['success' => true, 'html' => '<h1>Private</h1>', 'title' => 'Private'])]);
        $job = new class('http://169.254.169.254/latest/meta-data', new ScraperSource(['name' => 'Test'])) extends ScrapeJobDetailsJob
        {
            protected function recordMetric(bool $isSuccess): void {}
        };
        $failed = false;
        try {
            $job->handle();
        } catch (\Exception) {
            $failed = true;
        }
        $this->assertTrue($failed);
        Http::assertNothingSent();
    }
}
