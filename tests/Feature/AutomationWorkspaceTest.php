<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomationWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_csv_deep_link_keeps_both_tools_visible_and_renders_import_controls(): void
    {
        Setting::set('monetization_enabled', false);

        $this->actingAs(User::factory()->create())
            ->get('/automation?tab=csv')
            ->assertOk()
            ->assertSee('data-initial-section="csv"', false)
            ->assertSee('id="automation-extension"', false)
            ->assertSee('id="automation-csv"', false)
            ->assertSee('id="csvImportForm"', false)
            ->assertSee("String(row.application_date || '').slice(0, 10)", false)
            ->assertSee(route('csv.template'), false)
            ->assertSee(route('csv.export'), false);
    }

    public function test_free_account_sees_upgrade_instead_of_premium_download_and_import(): void
    {
        Setting::set('monetization_enabled', true);

        $this->actingAs(User::factory()->create(['is_premium' => false]))
            ->get('/automation')
            ->assertOk()
            ->assertSee(route('payment.premium'), false)
            ->assertDontSee('id="csvImportForm"', false)
            ->assertDontSee('downloads/trakerja-extension.zip', false);
    }
}
