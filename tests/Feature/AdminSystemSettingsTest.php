<?php

namespace Tests\Feature;

use App\Livewire\Admin\{DatabaseMaintenance, GlobalSettings, IntegrationHub};
use App\Models\{Setting, User};
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Cache, Storage};
use Livewire\Livewire;
use Tests\TestCase;

class AdminSystemSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_one_page_contains_all_sections_and_the_sidebar_has_one_settings_entry(): void
    {
        $response = $this->get(route('admin.settings'))->assertOk();
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        foreach (['general', 'integrations', 'maintenance'] as $id) {
            $this->assertSame(1, $xpath->query('//section[@id="'.$id.'"]')->length);
        }
        $this->assertSame(1, $xpath->query('//*[@id="admin-sidebar"]//a[@href="'.route('admin.settings').'"]')->length);
        foreach (['admin.integration-hub' => 'integrations', 'admin.database-maintenance' => 'maintenance'] as $route => $fragment) {
            $this->assertSame(0, $xpath->query('//*[@id="admin-sidebar"]//a[@href="'.route($route).'"]')->length);
            $this->get(route($route))->assertRedirect(route('admin.settings').'#'.$fragment);
        }
        $this->get(route('admin.settings', ['tab' => 'database']))->assertOk();
        $this->get(route('admin.settings', ['tab' => ['invalid']]))->assertOk();
        $response->assertDontSee('Daily snapshots active')->assertDontSee('Production Secure')->assertSee('SQL export unavailable');
    }

    public function test_identity_and_defaults_save_independently_and_validate_before_writing(): void
    {
        Setting::set('limit_ai_analyzer_free', 7);
        Livewire::test(GlobalSettings::class)->set('appName', 'Team workspace')->set('contactEmail', 'invalid')->call('updateIdentity')->assertHasErrors('contactEmail');
        $this->assertDatabaseMissing('settings', ['key' => 'app_name']);
        Livewire::test(GlobalSettings::class)->set('appName', 'Team workspace')->set('contactEmail', 'support@example.test')->call('updateIdentity')->assertHasNoErrors();
        $this->assertSame('Team workspace', Setting::get('app_name'));
        $this->assertSame(7, Setting::get('limit_ai_analyzer_free'));
        Livewire::test(GlobalSettings::class)->set('aiLimitFree', 3)->set('aiLimitPremium', 10)->set('jobLimitFree', 0)->call('updateLimits')->assertHasErrors('jobLimitFree')
            ->set('jobLimitFree', 20)->call('updateLimits')->assertHasNoErrors();
        $this->assertSame(3, Setting::get('limit_ai_analyzer_free'));
        $this->assertSame('Team workspace', Setting::get('app_name'));
    }

    public function test_integrations_show_configuration_without_disclosing_provider_secrets(): void
    {
        config(['services.gemini.key' => 'private-gemini-test-value', 'pakasir.api_key' => 'private-pakasir-test-value']);
        Livewire::test(IntegrationHub::class)->assertSee('Gemini')->assertSee('Pakasir')
            ->assertSee('Configured')->assertDontSee('private-gemini-test-value', false)->assertDontSee('private-pakasir-test-value', false)
            ->set('webhookEnabled', true)->set('webhookUrl', 'javascript:alert(1)')->call('saveWebhook')->assertHasErrors('webhookUrl')
            ->set('webhookUrl', 'https://example.test/events')->call('saveWebhook')->assertHasNoErrors();
        $this->assertTrue(Setting::get('webhook_enabled'));
        $this->assertSame('https://example.test/events', Setting::get('webhook_url'));
    }

    public function test_the_api_key_changes_only_when_the_action_is_explicitly_called(): void
    {
        Setting::set('api_key', 'previous-test-key');
        $component = Livewire::test(IntegrationHub::class)->assertSet('apiKey', 'previous-test-key');
        $this->assertSame('previous-test-key', Setting::get('api_key'));
        $component->call('generateApiKey')->assertDispatched('api-key-generated');
        $key = Setting::get('api_key');
        $this->assertStringStartsWith('tk_', $key);
        $this->assertSame(35, strlen($key));
        $this->assertNotSame('previous-test-key', $key);
    }

    public function test_storage_cleanup_only_deletes_unreferenced_public_logo_files(): void
    {
        $disk = Storage::disk('public');
        foreach (['logos/active-relative.jpg', 'logos/active-url.jpg', 'logos/unused.jpg', 'logos/.gitignore', 'cv_exports/keep.pdf'] as $path) $disk->put($path, 'test file');
        User::factory()->create(['logo' => 'logos/active-relative.jpg']);
        User::factory()->create(['logo' => 'https://example.test/storage/logos/active-url.jpg']);
        Livewire::test(DatabaseMaintenance::class)->assertSet('databaseDriver', 'sqlite')->assertSet('unusedFilesCount', 1)
            ->assertSet('unusedBytes', 9)->assertSee('SQL export unavailable')->call('cleanStorage')->assertSet('unusedFilesCount', 0)->assertDispatched('storage-cleaned');
        $disk->assertMissing('logos/unused.jpg');
        foreach (['logos/active-relative.jpg', 'logos/active-url.jpg', 'logos/.gitignore', 'cv_exports/keep.pdf'] as $path) $disk->assertExists($path);
    }

    public function test_storage_is_rescanned_before_cleanup_so_new_references_are_preserved(): void
    {
        Storage::disk('public')->put('logos/new-reference.jpg', 'test');
        $component = Livewire::test(DatabaseMaintenance::class)->assertSet('unusedFilesCount', 1);
        User::factory()->create(['logo' => 'logos/new-reference.jpg']);
        $component->call('cleanStorage')->assertSet('unusedFilesCount', 0);
        Storage::disk('public')->assertExists('logos/new-reference.jpg');
    }

    public function test_failed_scan_blocks_deletion_and_shows_an_unavailable_state(): void
    {
        $disk = \Mockery::mock(FilesystemAdapter::class);
        $disk->shouldReceive('files')->with('logos')->andThrow(new \RuntimeException('storage unavailable'));
        $disk->shouldNotReceive('delete');
        Storage::shouldReceive('disk')->with('public')->andReturn($disk);
        Livewire::test(DatabaseMaintenance::class)->assertSet('scanFailed', true)->assertSet('unusedFilesCount', 0)
            ->call('cleanStorage')->assertHasErrors('cleanup')->assertNotDispatched('storage-cleaned');
    }

    public function test_maintenance_state_is_real_and_admins_keep_access(): void
    {
        $admin = auth()->user();
        $component = Livewire::test(DatabaseMaintenance::class)->assertSet('maintenanceMode', false);
        $component->call('toggleMaintenance')->assertSet('maintenanceMode', true)->assertDispatched('maintenance-updated');
        $this->assertTrue(Setting::get('maintenance_mode'));
        $this->get(route('admin.settings'))->assertOk();
        $this->actingAs(User::factory()->create())->get(route('dashboard'))->assertStatus(503);
        $this->actingAs($admin);
        $component->call('toggleMaintenance')->assertSet('maintenanceMode', false);
        $this->assertFalse(Setting::get('maintenance_mode'));
    }

    public function test_regular_members_cannot_load_or_change_system_settings(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['admin.settings', 'admin.integration-hub', 'admin.database-maintenance'] as $route) $this->get(route($route))->assertForbidden();
        foreach ([GlobalSettings::class, IntegrationHub::class, DatabaseMaintenance::class] as $component) Livewire::test($component)->assertForbidden();
    }
}
