<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class JobScrapeUrlSecurityTest extends TestCase
{
    use RefreshDatabase;

    public static function forbiddenUrls(): array
    {
        return array_map(fn ($url) => [$url], [
            'http://127.0.0.1/admin', 'http://10.0.0.1/',
            'http://169.254.169.254/latest/meta-data/', 'http://localhost/',
            'http://[::1]/', 'http://2130706433/', 'http://0177.0.0.1/',
            'https://linkedin.com.attacker.test/', 'https://attacker.test/linkedin.com',
            'https://linkedin.com@127.0.0.1/', 'https://user:pass@linkedin.com/',
            'https://linkedin.com:8443/', 'https://linkedin.com./',
            'https://linkedin.com\\@127.0.0.1/', 'ftp://linkedin.com/jobs',
        ]);
    }

    #[DataProvider('forbiddenUrls')]
    public function test_unsafe_destination_is_rejected_without_network_request(string $url): void
    {
        Http::fake(['*' => Http::response('<title>Private service</title>', 200)]);
        Http::preventStrayRequests();
        $this->actingAs(User::factory()->create())->postJson('/jobs/scrape-url', ['url' => $url])
            ->assertStatus(422);
        Http::assertNothingSent();
    }

    public static function nonPublicAddresses(): array
    {
        return array_map(fn ($ip) => [$ip], ['127.0.0.1', '10.1.2.3', '172.16.0.1',
            '192.168.1.1', '169.254.169.254', '100.64.0.1', '192.0.2.1',
            '198.18.0.1', '224.0.0.1', '240.0.0.1', '::1', 'fc00::1',
            'fe80::1', '::ffff:127.0.0.1', '2001:db8::1', '2002:7f00:1::']);
    }

    private function resolveTo(array $addresses): void
    {
        $resolver = \Mockery::mock(\App\Services\PublicDnsResolver::class);
        $resolver->shouldReceive('resolve')->andReturn($addresses);
        $this->app->instance(\App\Services\PublicDnsResolver::class, $resolver);
    }

    #[DataProvider('nonPublicAddresses')]
    public function test_supported_domain_with_nonpublic_dns_is_rejected(string $ip): void
    {
        $this->resolveTo(['93.184.216.34', $ip]);
        Http::fake(['*' => Http::response('<title>Private service</title>')]);
        $this->actingAs(User::factory()->create())->postJson('/jobs/scrape-url', ['url' => 'https://linkedin.com/jobs'])
            ->assertStatus(422);
        Http::assertNothingSent();
    }

    public function test_redirect_to_private_target_is_not_followed(): void
    {
        $this->resolveTo(['93.184.216.34']);
        Http::fake(['*' => Http::response('', 302, ['Location' => 'http://127.0.0.1/admin'])]);
        $this->actingAs(User::factory()->create())->postJson('/jobs/scrape-url', ['url' => 'https://linkedin.com/jobs'])
            ->assertStatus(422);
        Http::assertSentCount(1);
    }

    public function test_public_page_is_parsed_and_transport_is_pinned(): void
    {
        $this->resolveTo(['93.184.216.34']);
        Http::fake(['*' => Http::response('<script type="application/ld+json">{"@type":"JobPosting","title":"Engineer","hiringOrganization":{"name":"Example"}}</script>')]);
        $observed = false;
        Http::globalMiddleware(function ($handler) use (&$observed) {
            return function ($request, $options) use ($handler, &$observed) {
                $observed = true;
                $this->assertFalse($options['allow_redirects']);
                $this->assertSame('', $options['proxy']);
                $this->assertSame(['linkedin.com:443:93.184.216.34'], $options['curl'][CURLOPT_RESOLVE]);
                $this->assertTrue($options['verify']);
                $this->assertTrue($options['curl'][CURLOPT_FRESH_CONNECT]);
                $this->assertTrue($options['curl'][CURLOPT_FORBID_REUSE]);
                $this->assertSame(CURLPROTO_HTTP | CURLPROTO_HTTPS, $options['curl'][CURLOPT_PROTOCOLS]);
                return $handler($request, $options);
            };
        });
        $this->actingAs(User::factory()->create())->postJson('/jobs/scrape-url', ['url' => 'https://linkedin.com/jobs'])
            ->assertOk()->assertJsonPath('job_title', 'Engineer')->assertJsonPath('company_name', 'Example');
        $this->assertTrue($observed);
    }

    public function test_empty_dns_answer_is_rejected_without_request(): void
    {
        $this->resolveTo([]);
        Http::fake();
        $this->actingAs(User::factory()->create())->postJson('/jobs/scrape-url', ['url' => 'https://linkedin.com/jobs'])
            ->assertStatus(422)->assertJsonValidationErrors('url');
        Http::assertNothingSent();
    }

    public function test_relative_redirect_is_revalidated_before_public_page_is_parsed(): void
    {
        $resolver = \Mockery::mock(\App\Services\PublicDnsResolver::class);
        $resolver->shouldReceive('resolve')->with('linkedin.com')->twice()->andReturn(['93.184.216.34']);
        $this->app->instance(\App\Services\PublicDnsResolver::class, $resolver);
        Http::fake([
            'https://linkedin.com/jobs' => Http::response('', 302, ['Location' => '/jobs/42']),
            'https://linkedin.com/jobs/42' => Http::response('<title>Engineer</title>'),
        ]);
        Http::preventStrayRequests();
        $this->actingAs(User::factory()->create())->postJson('/jobs/scrape-url', ['url' => 'https://linkedin.com/jobs'])
            ->assertOk()->assertJsonPath('job_title', 'Engineer');
        Http::assertSentCount(2);
    }

    public function test_redirect_rebinding_to_nonpublic_dns_is_rejected(): void
    {
        $resolver = \Mockery::mock(\App\Services\PublicDnsResolver::class);
        $resolver->shouldReceive('resolve')->with('linkedin.com')->once()->andReturn(['93.184.216.34']);
        $resolver->shouldReceive('resolve')->with('www.linkedin.com')->once()->andReturn(['10.0.0.1']);
        $this->app->instance(\App\Services\PublicDnsResolver::class, $resolver);
        Http::fake(['*' => Http::response('', 302, ['Location' => 'https://www.linkedin.com/jobs'])]);
        $this->actingAs(User::factory()->create())->postJson('/jobs/scrape-url', ['url' => 'https://linkedin.com/jobs'])
            ->assertStatus(422);
        Http::assertSentCount(1);
    }

    public function test_redirect_loop_is_bounded(): void
    {
        $this->resolveTo(['93.184.216.34']);
        Http::fake(['*' => Http::response('', 302, ['Location' => '/jobs'])]);
        $this->actingAs(User::factory()->create())->postJson('/jobs/scrape-url', ['url' => 'https://linkedin.com/jobs'])
            ->assertStatus(422);
        Http::assertSentCount(4);
    }

    public function test_unsupported_domain_redirect_is_rejected(): void
    {
        $this->resolveTo(['93.184.216.34']);
        Http::fake(['*' => Http::response('', 302, ['Location' => 'https://attacker.test/jobs'])]);
        $this->actingAs(User::factory()->create())->postJson('/jobs/scrape-url', ['url' => 'https://linkedin.com/jobs'])
            ->assertStatus(422);
        Http::assertSentCount(1);
    }
}
