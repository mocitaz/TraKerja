<?php

namespace Tests\Feature;

use App\Services\PublicDnsResolver;
use App\Services\ScraperPageFetcher;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/** The autofill renderer client (ScraperPageFetcher) must never reach private addresses. */
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
}
