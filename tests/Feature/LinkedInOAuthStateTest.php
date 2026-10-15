<?php

namespace Tests\Feature;

use App\Http\Middleware\CheckSurveyRedirect;
use App\Http\Middleware\SoftMaintenanceMode;
use App\Http\Middleware\UpdateLastActivity;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\LinkedInOpenIdProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LinkedInOAuthStateTest extends TestCase
{
    private array $providerRequests = [];

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
        config([
            'app.key' => str_repeat('t', 32),
            'session.driver' => 'array',
            'cache.default' => 'array',
            'logging.default' => 'null',
        ]);
        $this->withoutMiddleware([SoftMaintenanceMode::class, UpdateLastActivity::class, CheckSurveyRedirect::class]);

        // Exercise Socialite's actual state validation; only provider HTTP is simulated.
        $stack = HandlerStack::create(new MockHandler([
            new Response(400, ['Content-Type' => 'application/json'], '{"error":"invalid_grant"}'),
            new Response(400, ['Content-Type' => 'application/json'], '{"error":"invalid_grant"}'),
        ]));
        $stack->push(Middleware::history($this->providerRequests));
        $client = new Client(['handler' => $stack]);
        Socialite::shouldReceive('driver')->with('linkedin-openid')->andReturnUsing(function () use ($client) {
            return (new LinkedInOpenIdProvider(
                $this->app['request'], 'test-client-id', 'test-client-secret',
                'http://localhost/auth/linkedin/callback'
            ))->setHttpClient($client);
        });
    }

    public function test_redirect_stores_state_in_session_and_sends_it_to_linkedin(): void
    {
        $response = $this->get('/auth/linkedin')->assertRedirect();
        parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);
        $this->assertSame('www.linkedin.com', parse_url($response->headers->get('Location'), PHP_URL_HOST));
        $this->assertNotEmpty($query['state'] ?? null);
        $response->assertSessionHas('state', $query['state']);
        $this->assertSame([], $this->providerRequests);
        $this->assertGuest();
    }

    public static function invalidStates(): array
    {
        return [
            'no state anywhere' => [null, null],
            'missing callback state' => ['session-state', null],
            'empty callback state' => ['session-state', ''],
            'missing session state' => [null, 'callback-state'],
            'mismatched or different browser state' => ['victim-session', 'attacker-session'],
        ];
    }

    #[DataProvider('invalidStates')]
    public function test_invalid_state_is_rejected_before_any_provider_request(?string $sessionState, ?string $callbackState): void
    {
        if ($sessionState !== null) {
            $this->withSession(['state' => $sessionState]);
        }
        $query = ['code' => 'synthetic-authorization-code'];
        if ($callbackState !== null) {
            $query['state'] = $callbackState;
        }

        $this->get('/auth/linkedin/callback?'.http_build_query($query))
            ->assertRedirect('/login')->assertSessionHasErrors('email')->assertSessionMissing('state');
        $this->assertSame([], $this->providerRequests);
        $this->assertGuest();
    }

    public function test_matching_state_reaches_provider_and_cannot_be_replayed(): void
    {
        $this->get('/auth/linkedin')->assertRedirect();
        $state = session('state');
        $url = '/auth/linkedin/callback?'.http_build_query(['state' => $state, 'code' => 'synthetic-code']);

        // A provider error avoids any account/database work after the validated exchange.
        $this->get($url)->assertRedirect('/login')->assertSessionMissing('state');
        $this->assertCount(1, $this->providerRequests);
        $this->assertSame('POST', $this->providerRequests[0]['request']->getMethod());
        $this->assertSame('/oauth/v2/accessToken', $this->providerRequests[0]['request']->getUri()->getPath());
        $this->get($url)->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertCount(1, $this->providerRequests);
        $this->assertGuest();
    }

    public function test_callback_errors_do_not_log_authorization_payloads(): void
    {
        Log::spy();
        $this->withSession(['state' => 'matching-state'])->get('/auth/linkedin/callback?'.http_build_query([
            'state' => 'matching-state', 'code' => 'synthetic-code-not-for-logs',
        ]))->assertRedirect('/login');

        Log::shouldHaveReceived('error')->once()->withArgs(function ($message, $context) {
            $this->assertArrayNotHasKey('request', $context);
            $this->assertStringNotContainsString('synthetic-code-not-for-logs', json_encode($context));

            return true;
        });
    }
}
