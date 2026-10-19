<?php

namespace Tests\Feature;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LandingPageTest extends TestCase
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

    public function test_guest_can_read_both_languages_and_every_local_anchor_exists(): void
    {
        Cache::put('setting_monetization_enabled', true);
        Cache::put('setting_premium_price', 27000);
        foreach (['en', 'id'] as $locale) {
            app()->setLocale($locale);
            $html = view('welcome')->render();
            $document = new \DOMDocument;
            @$document->loadHTML('<?xml encoding="UTF-8">'.$html);
            $this->assertSame(1, $document->getElementsByTagName('h1')->length);
            $this->assertStringNotContainsString('landing.', strip_tags($html));
            $this->assertStringNotContainsString('user-scalable=no', $html);
            $this->assertStringContainsString('27.000', $html);
            $this->assertStringContainsString('data-demo-player', $html);
            $this->assertStringContainsString('id="testimonials"', $html);
            foreach (['Bintang Syafrian Rizal', 'Aretha', 'Al Ferro Putra'] as $name) $this->assertStringContainsString($name, $html);
            foreach ($document->getElementsByTagName('a') as $link) {
                $href = $link->getAttribute('href');
                if (str_starts_with($href, '#')) {
                    $target = substr($href, 1);
                    $this->assertNotEmpty($target);
                    $this->assertNotNull($document->getElementById($target), $href);
                }
            }
            $xpath = new \DOMXPath($document);
            $sources = array_unique(array_map(fn ($source) => $source === 'JobsDB' ? 'JobStreet' : $source, \App\Support\PlatformCatalog::OPTIONS));
            $this->assertSame(count($sources), $xpath->query('//*[@data-application-source]')->length);
            $this->assertSame(1, $xpath->query('//*[@data-application-source="JobStreet"]')->length);
            $this->assertStringNotContainsString('data-application-source="JobsDB"', $html);
            foreach ($sources as $source) {
                $this->assertStringContainsString('data-application-source="'.$source.'"', $html);
            }
            foreach ($xpath->query('//*[@role="tab"]') as $tab) {
                $panel = $document->getElementById($tab->getAttribute('aria-controls'));
                $this->assertNotNull($panel);
                $this->assertSame('tabpanel', $panel->getAttribute('role'));
                $this->assertSame($tab->getAttribute('id'), $panel->getAttribute('aria-labelledby'));
            }
        }
    }

    public function test_free_mode_does_not_advertise_a_paid_checkout(): void
    {
        Cache::put('setting_monetization_enabled', false);
        Cache::put('setting_premium_price', 27000);
        $html = view('welcome')->render();
        $this->assertStringNotContainsString('data-paid-plan', $html);
        $this->assertStringContainsString('Rp 0', $html);
        $this->assertStringContainsString('Rp 27.000', $html);
        $this->assertStringContainsString('Rp 14.999', $html);
        $this->assertStringContainsString(__('landing.plans.paused'), $html);
        $this->assertStringNotContainsString('href="'.route('payment.index').'"', $html);
        $this->assertStringNotContainsString('href="'.route('payment.topup').'"', $html);
        $this->assertStringNotContainsString('50+ premium templates', $html);
    }

    public function test_landing_catalogues_have_matching_keys_and_placeholders(): void
    {
        $flatten = function (array $items, string $prefix = '') use (&$flatten): array {
            $result = [];
            foreach ($items as $key => $value) {
                if (is_array($value)) $result += $flatten($value, $prefix.$key.'.');
                else $result[$prefix.$key] = $value;
            }
            return $result;
        };
        $en = $flatten(require lang_path('en/landing.php'));
        $id = $flatten(require lang_path('id/landing.php'));
        $this->assertSame(array_keys($en), array_keys($id));
        foreach ($en as $key => $value) {
            $this->assertNotSame('', trim($id[$key]), $key);
            preg_match_all('/:[a-z_]+/', $value, $first);
            preg_match_all('/:[a-z_]+/', $id[$key], $second);
            $this->assertSame($first[0], $second[0], $key);
        }
    }

    public function test_signed_in_actions_open_existing_workspaces(): void
    {
        Cache::put('setting_monetization_enabled', false);
        \Illuminate\Support\Facades\Auth::setUser(new \App\Models\User(['name' => 'Preview user']));
        $html = view('welcome')->render();
        $this->assertStringContainsString('href="'.url('/dashboard').'"', $html);
        $this->assertStringContainsString('href="'.url('/cv/builder').'"', $html);
        $this->assertStringContainsString('href="'.url('/ai-studio').'"', $html);
        $this->assertStringNotContainsString('href="'.route('register').'"', $html);
    }
}
