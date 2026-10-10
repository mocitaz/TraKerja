<?php

namespace Tests\Feature;

use App\Models\DataExport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UtilityPagesWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_legal_documents_have_linked_contents_and_real_contact(): void
    {
        foreach (['terms-of-service' => 6, 'privacy-policy' => 5] as $document => $count) {
            $response = $this->get('/'.$document)->assertOk()
                ->assertSee('On this page')->assertSee('trakerja@teknalogi.id')
                ->assertDontSee('Last updated:')->assertSee('Skip to document');
            for ($section = 1; $section <= $count; $section++) {
                $response->assertSee('href="#section-'.$section.'"', false)
                    ->assertSee('id="section-'.$section.'"', false);
            }
        }
    }

    public function test_error_pages_render_without_account_and_offer_appropriate_recovery(): void
    {
        foreach ([403, 404, 419, 429, 500, 503] as $code) {
            $html = view('errors.'.$code)->render();
            $this->assertStringContainsString('id="error-title"', $html);
            $this->assertStringContainsString('error-code">'.$code, $html);
            $this->assertStringContainsString('Contact support', $html);
            $this->assertStringNotContainsString('<canvas', $html);
            $this->assertStringContainsString('error-workspace-art', $html);
            $this->assertStringContainsString('images/icon.png', $html);
            $this->assertStringContainsString('PT Teknalogi Transformasi Digital', $html);
            $this->assertStringNotContainsString('utility-topbar', $html);
            $this->assertStringNotContainsString('history.back()', $html);
            if ($code === 419) {
                $this->assertStringContainsString(route('login'), $html);
                $this->assertStringContainsString('Sign in again', $html);
            }
        }
        config(['app.debug' => false]);
        $this->get('/this-page-does-not-exist')->assertNotFound()->assertSee('We couldn’t find this page');
    }

    public function test_export_lifecycle_uses_saved_status_and_expiry_without_premature_download(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $export = DataExport::create(['user_id' => $user->id, 'kind' => 'jobs', 'status' => 'queued', 'filters' => [], 'max_source_id' => 0, 'row_count' => 150, 'expires_at' => now()->addDay()]);
        foreach (['queued' => 'Your export is queued', 'processing' => 'Preparing your CSV', 'ready' => 'Your CSV is ready', 'failed' => 'Export failed'] as $state => $label) {
            $export->update(['status' => $state]);
            $response = $this->get(route('data-exports.show', $export->id))->assertOk()
                ->assertSee($label);
            $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
            $this->assertStringContainsString('private', $response->headers->get('Cache-Control'));
            if ($state === 'ready') {
                $response->assertSee('Download CSV · 150 rows')->assertSee(route('data-exports.download', $export->id));
            } else {
                $response->assertDontSee(route('data-exports.download', $export->id));
            }
        }
        $export->update(['status' => 'ready', 'expires_at' => now()->subMinute()]);
        $this->get(route('data-exports.show', $export->id))->assertOk()
            ->assertSee('This export has expired')->assertDontSee('Download CSV')->assertSee('Create a new export');
        $this->actingAs(User::factory()->create())->get(route('data-exports.show', $export->id))->assertNotFound();
    }
}
