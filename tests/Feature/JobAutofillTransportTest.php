<?php

namespace Tests\Feature;

use App\Http\Controllers\JobScraperController;
use App\Services\PublicDnsResolver;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class JobAutofillTransportTest extends TestCase
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
        config(['app.key' => str_repeat('j', 32), 'cache.default' => 'array', 'logging.default' => 'null', 'scraper.microservice_url' => null, 'scraper.service_token' => null]);
        Http::preventStrayRequests();
        $dns = \Mockery::mock(PublicDnsResolver::class);
        $dns->shouldReceive('resolve')->andReturn(['93.184.216.34']);
        $this->app->instance(PublicDnsResolver::class, $dns);
    }

    public function test_url_and_html_endpoints_share_the_same_complete_extraction(): void
    {
        $html = '<script type="application/ld+json">{"@type":"JobPosting","title":"Engineer","hiringOrganization":{"name":"Example"},"description":"Build APIs","employmentType":"CONTRACT"}</script>';
        Http::fake(['*' => Http::response($html)]);
        $controller = app(JobScraperController::class);
        $url = 'https://glints.com/id/opportunities/jobs/123';
        $direct = $controller->scrapeUrl(Request::create('/', 'POST', ['url' => $url]));
        $uploaded = $controller->scrapeHtml(Request::create('/', 'POST', ['url' => $url, 'html' => $html]));
        $this->assertSame(200, $direct->getStatusCode());
        $this->assertSame($direct->getData(true), $uploaded->getData(true));
        $this->assertSame('Contract', $direct->getData(true)['career_level']);
        Http::assertSentCount(1);
    }

    public function test_private_targets_are_rejected_before_transport(): void
    {
        $this->expectException(ValidationException::class);
        try {
            app(JobScraperController::class)->scrapeUrl(Request::create('/', 'POST', ['url' => 'http://127.0.0.1/admin']));
        } finally {
            Http::assertNothingSent();
        }
    }

    public function test_blocked_portal_response_is_actionable_and_does_not_expose_html(): void
    {
        Http::fake(['*' => Http::response('Sensitive provider debug body', 403)]);
        $response = app(JobScraperController::class)->scrapeUrl(Request::create('/', 'POST', ['url' => 'https://linkedin.com/jobs/view/123']));
        $this->assertSame(422, $response->getStatusCode());
        $this->assertFalse($response->getData(true)['success']);
        $this->assertStringNotContainsString('Sensitive provider', $response->getContent());
        Http::assertSentCount(1);
    }

    public function test_configured_safe_renderer_can_recover_a_javascript_only_page(): void
    {
        config(['scraper.microservice_url'=>'http://renderer.test','scraper.service_token'=>'synthetic-token']);
        Http::fake(['*'=>Http::response('<title>Glints</title><div id="root"></div>')]);
        $renderer=\Mockery::mock(\App\Services\ScraperPageFetcher::class);
        $renderer->shouldReceive('fetch')->with('https://glints.com/id/opportunities/jobs/123')->once()
            ->andReturn(['html'=>'<script type="application/ld+json">{"@type":"JobPosting","title":"Engineer","hiringOrganization":{"name":"Example"}}</script>','title'=>'Engineer']);
        $this->app->instance(\App\Services\ScraperPageFetcher::class,$renderer);
        $response=app(JobScraperController::class)->scrapeUrl(Request::create('/','POST',['url'=>'https://glints.com/id/opportunities/jobs/123']));
        $this->assertSame(200,$response->getStatusCode());
        $this->assertSame('Example',$response->getData(true)['company_name']);
    }
}
