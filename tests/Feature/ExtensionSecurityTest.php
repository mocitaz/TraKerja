<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ExtensionSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['sanctum.guard' => []]);
        Http::fake();
        Http::preventStrayRequests();
        Mail::fake();
    }

    public function test_invalid_credentials_do_not_issue_tokens(): void
    {
        $user = User::factory()->create();
        foreach ([$user->email, 'unknown@example.test'] as $email) {
            $this->postJson('/api/extension/login', ['email' => $email, 'password' => 'wrong'])->assertUnauthorized();
        }
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_unverified_accounts_cannot_login_even_if_admin(): void
    {
        $user = User::factory()->unverified()->create(['role' => 'admin']);
        $this->postJson('/api/extension/login', ['email' => $user->email, 'password' => 'password'])->assertForbidden();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_login_normalizes_email_and_issues_scoped_expiring_token(): void
    {
        $this->freezeTime();
        $user = User::factory()->create(['email' => 'member@example.test']);
        $response = $this->postJson('/api/extension/login', ['email' => ' MEMBER@EXAMPLE.TEST ', 'password' => 'password'])->assertOk();
        $token = PersonalAccessToken::findToken($response->json('token'));
        $this->assertSame($user->id, $token->tokenable_id);
        $this->assertSame(['jobs:create', 'account:read', 'token:revoke'], $token->abilities);
        $this->assertSame(now()->addDays(30)->toDateTimeString(), $token->expires_at->toDateTimeString());
        $this->assertNotSame($response->json('token'), $token->token);
    }

    public function test_account_limit_is_normalized_and_shared_across_ips(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.'.($i + 1)])
                ->postJson('/api/extension/login', ['email' => $i % 2 ? ' TARGET@EXAMPLE.TEST ' : 'target@example.test', 'password' => 'wrong'])
                ->assertUnauthorized();
        }
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.99'])
            ->postJson('/api/extension/login', ['email' => 'target@example.test', 'password' => 'wrong'])
            ->assertStatus(429)->assertHeader('Retry-After');
        $this->postJson('/api/extension/login', ['email' => 'other@example.test', 'password' => 'wrong'])->assertUnauthorized();
        $this->travel(61)->seconds();
        $this->postJson('/api/extension/login', ['email' => 'target@example.test', 'password' => 'wrong'])->assertUnauthorized();
    }

    public function test_ip_limit_applies_across_accounts(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->postJson('/api/extension/login', ['email' => "target{$i}@example.test", 'password' => 'wrong'])->assertUnauthorized();
        }
        $this->postJson('/api/extension/login', ['email' => 'fresh@example.test', 'password' => 'wrong'])->assertStatus(429);
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.2'])
            ->postJson('/api/extension/login', ['email' => 'fresh@example.test', 'password' => 'wrong'])->assertUnauthorized();
    }

    public static function endpoints(): array
    {
        return [
            'account' => ['GET', '/api/user', 'account:read'],
            'save job' => ['POST', '/api/extension/jobs', 'jobs:create'],
            'logout' => ['POST', '/api/extension/logout', 'token:revoke'],
        ];
    }

    #[DataProvider('endpoints')]
    public function test_guests_cannot_use_protected_endpoints(string $method, string $url): void
    {
        $this->json($method, $url)->assertUnauthorized();
    }

    #[DataProvider('endpoints')]
    public function test_tokens_missing_required_ability_are_denied(string $method, string $url, string $ability): void
    {
        $token = User::factory()->create()->createToken('restricted', array_values(array_diff(['jobs:create', 'account:read', 'token:revoke'], [$ability])));
        $this->withToken($token->plainTextToken)->json($method, $url)->assertForbidden();
        $this->assertDatabaseCount('job_applications', 0);
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    #[DataProvider('endpoints')]
    public function test_legacy_tokens_for_unverified_users_are_denied(string $method, string $url): void
    {
        $token = User::factory()->unverified()->create(['role' => 'admin'])->createToken('legacy');
        $this->withToken($token->plainTextToken)->json($method, $url)->assertForbidden();
        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_verification_removed_after_token_issue_is_enforced(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('extension', ['account:read']);
        $this->withToken($token->plainTextToken)->getJson('/api/user')->assertOk();
        $user->update(['email_verified_at' => null]);
        $this->app['auth']->forgetGuards();
        $this->getJson('/api/user')->assertForbidden();
    }

    public function test_issued_token_can_read_account_and_logout_only_revokes_itself(): void
    {
        $user = User::factory()->create();
        $other = $user->createToken('other', ['account:read']);
        $response = $this->postJson('/api/extension/login', ['email' => $user->email, 'password' => 'password'])->assertOk();
        $this->withToken($response->json('token'))->getJson('/api/user')->assertOk()->assertJsonPath('id', $user->id);
        $this->app['auth']->forgetGuards();
        $this->postJson('/api/extension/logout')->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->assertNotNull(PersonalAccessToken::findToken($other->plainTextToken));
        $this->app['auth']->forgetGuards();
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_real_token_expires_after_thirty_days(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/extension/login', ['email' => $user->email, 'password' => 'password'])->assertOk();
        $this->withToken($response->json('token'))->getJson('/api/user')->assertOk();
        $this->travel(30)->days();
        $this->travel(1)->seconds();
        $this->app['auth']->forgetGuards();
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_issued_token_can_save_a_job_for_its_owner(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/extension/login', ['email' => $user->email, 'password' => 'password'])->assertOk();
        $this->withToken($response->json('token'))->postJson('/api/extension/jobs', [
            'company_name' => 'Example Company',
            'position' => 'Developer',
            'location' => 'Jakarta',
            'career_level' => 'Entry Level',
            'platform' => 'Company site',
            'platform_link' => 'https://example.test/jobs/1',
        ])->assertCreated();
        $this->assertDatabaseHas('job_applications', ['user_id' => $user->id, 'company_name' => 'Example Company']);
    }

    #[DataProvider('endpoints')]
    public function test_expired_tokens_cannot_use_any_endpoint(string $method, string $url): void
    {
        $token = User::factory()->create()->createToken('expired', ['*'], now()->subSecond());
        $this->withToken($token->plainTextToken)->json($method, $url)->assertUnauthorized();
    }

    #[DataProvider('endpoints')]
    public function test_revoked_tokens_cannot_use_any_endpoint(string $method, string $url): void
    {
        $token = User::factory()->create()->createToken('revoked');
        $token->accessToken->delete();
        $this->withToken($token->plainTextToken)->json($method, $url)->assertUnauthorized();
    }

    #[DataProvider('endpoints')]
    public function test_legacy_tokens_without_expiry_are_rejected_after_thirty_days(string $method, string $url): void
    {
        $token = User::factory()->create()->createToken('legacy');
        $token->accessToken->forceFill(['created_at' => now()->subDays(31)])->save();
        $this->withToken($token->plainTextToken)->json($method, $url)->assertUnauthorized();
    }

    public function test_recent_legacy_token_with_explicit_ability_still_works(): void
    {
        $token = User::factory()->create()->createToken('legacy', ['account:read']);
        $token->accessToken->forceFill(['created_at' => now()->subDays(29)])->save();
        $this->withToken($token->plainTextToken)->getJson('/api/user')->assertOk();
    }

    public function test_future_expiry_cannot_extend_token_lifetime_past_thirty_days(): void
    {
        $token = User::factory()->create()->createToken('long-lived', ['account:read'], now()->addYear());
        $token->accessToken->forceFill(['created_at' => now()->subDays(31)])->save();
        $this->withToken($token->plainTextToken)->getJson('/api/user')->assertUnauthorized();
    }

    public function test_web_session_cannot_revoke_an_unrelated_token(): void
    {
        config(['sanctum.guard' => ['web']]);
        $user = User::factory()->create();
        $user->createToken('other');
        $this->actingAs($user)->postJson('/api/extension/logout')->assertForbidden();
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }
}
