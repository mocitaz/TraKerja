<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthPresentationTest extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(Kernel::class)->bootstrap();
        config(['app.key' => str_repeat('t', 32), 'app.url' => 'https://example.test']);
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        $this->withoutMiddleware(\App\Http\Middleware\UpdateLastActivity::class);
    }

    public function test_all_six_screens_share_the_shell_and_keep_their_original_form_contracts(): void
    {
        $screens = [
            ['/login', 'login', ['email', 'password', 'remember']],
            ['/register', 'register', ['name', 'email', 'password', 'password_confirmation']],
            ['/forgot-password', 'password.email', ['email']],
            ['/reset-password/sample-token?email=reader%40example.test', 'password.store', ['token', 'email', 'password', 'password_confirmation']],
        ];
        foreach (['en', 'id'] as $locale) {
            foreach ($screens as [$url, $action, $fields]) {
                $html = $this->withSession(['locale' => $locale])->get($url)->assertOk()->getContent();
                $this->assertScreen($html, $action, $fields);
            }
        }
        $user = new User(['name' => 'Test reader', 'email' => 'reader@example.test']);
        $user->id = 123;
        $this->actingAs($user);
        $this->assertScreen($this->get('/verify-email')->assertOk()->getContent(), 'verification.send', []);
        $html = $this->get('/confirm-password')->assertOk()->getContent();
        $this->assertScreen($html, 'password.confirm', ['password']);
        $this->assertStringNotContainsString('data-auth-provider', $html);
    }

    private function assertScreen(string $html, string $action, array $fields): void
    {
        $this->assertStringContainsString('class="auth-shell"', $html);
        $this->assertStringContainsString('images/auth/iphone-landing-cutout.webp', $html);
        $this->assertStringNotContainsString('class="auth-showcase-heading"', $html);
        $this->assertStringNotContainsString('class="auth-showcase-features"', $html);
        $this->assertStringContainsString('action="'.route($action).'"', $html);
        $this->assertStringContainsString('name="_token"', $html);
        $this->assertStringContainsString('href="'.route('legal.terms').'"', $html);
        $this->assertStringContainsString('href="'.route('legal.privacy').'"', $html);
        $this->assertStringNotContainsString('auth-ui.', strip_tags($html));
        foreach ($fields as $field) $this->assertStringContainsString('name="'.$field.'"', $html);
        $document = new \DOMDocument;
        @$document->loadHTML('<?xml encoding="UTF-8">'.$html);
        $this->assertSame(1, $document->getElementsByTagName('h1')->length);
        $xpath = new \DOMXPath($document);
        foreach ($xpath->query('//input[not(@type="hidden") and not(@type="checkbox")]') as $input) {
            $this->assertGreaterThan(0, $xpath->query('//label[@for="'.$input->getAttribute('id').'"]')->length);
        }
    }

    public function test_login_and_register_only_offer_google_linkedin_and_email(): void
    {
        foreach (['/login', '/register'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            foreach (['google', 'linkedin'] as $provider) {
                $this->assertStringContainsString('href="'.route('auth.'.$provider).'"', $html);
                $this->assertSame(1, substr_count($html, 'data-auth-provider="'.$provider.'"'));
            }
            foreach (['github', 'facebook', 'apple'] as $provider) $this->assertStringNotContainsString('data-auth-provider="'.$provider.'"', $html);
            $this->assertStringContainsString('<details class="auth-email"', $html);
        }
    }

    public function test_errors_reopen_email_form_preserve_safe_input_and_never_echo_passwords(): void
    {
        $html = $this->withSession([
            'errors' => (new \Illuminate\Support\ViewErrorBag)->put('default', new \Illuminate\Support\MessageBag(['email' => 'Check this email address.'])),
            '_old_input' => ['email' => 'reader@example.test', 'password' => 'do-not-echo-this'],
        ])->get('/login')->assertOk()->getContent();
        $this->assertMatchesRegularExpression('/<details class="auth-email"\s+open/', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('reader@example.test', $html);
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringNotContainsString('do-not-echo-this', $html);
    }

    public function test_reset_keeps_token_and_email_and_protected_screens_stay_protected(): void
    {
        $html = $this->get('/reset-password/sample-token?email=reader%40example.test')->assertOk()->getContent();
        $this->assertStringContainsString('name="token" value="sample-token"', $html);
        $this->assertStringContainsString('value="reader@example.test"', $html);
        $this->get('/verify-email')->assertRedirect(route('login'));
        $this->get('/confirm-password')->assertRedirect(route('login'));
    }

    public function test_turnstile_is_retained_on_production_forms_and_skipped_in_local_testing(): void
    {
        $this->get('/login')->assertDontSee('class="cf-turnstile"', false);
        $this->app->instance('env', 'production');
        foreach (['/login', '/register'] as $url) {
            $html = $this->get('https://example.test'.$url)->assertOk()->getContent();
            $this->assertStringContainsString('class="cf-turnstile"', $html);
            $this->assertStringContainsString('challenges.cloudflare.com/turnstile/v0/api.js', $html);
        }
    }

    public function test_auth_translations_have_matching_keys(): void
    {
        $flatten = function (array $values, string $prefix = '') use (&$flatten): array {
            $result = [];
            foreach ($values as $key => $value) {
                if (is_array($value)) $result += $flatten($value, $prefix.$key.'.');
                else $result[$prefix.$key] = $value;
            }
            return $result;
        };
        $en = $flatten(require lang_path('en/auth-ui.php'));
        $id = $flatten(require lang_path('id/auth-ui.php'));
        $this->assertSame(array_keys($en), array_keys($id));
        foreach ($id as $key => $value) $this->assertNotSame('', trim($value), $key);
    }
}
