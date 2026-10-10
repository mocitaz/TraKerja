<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_topbar_has_four_primary_links_and_tools_in_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get(route('admin.index'))->assertOk();
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $links = $xpath->query('//div[contains(@class,"workspace-desktop-links")]/a');
        $this->assertSame(4, $links->length);
        $this->assertSame(['Dashboard', 'User Management', 'Activity Log', 'Analytics'],
            array_map(fn ($link) => trim($link->textContent), iterator_to_array($links)));
        $this->assertSame('page', $links->item(0)->getAttribute('aria-current'));
        $this->assertSame(0, $xpath->query('//aside')->length);
        foreach (['admin.email-blast', 'admin.feedbacks.index', 'admin.survey.index', 'admin.scraper',
            'admin.settings', 'admin.payments', 'admin.monetization'] as $route) {
            $this->assertSame(1, $xpath->query('//*[@id="admin-profile-menu"]//a[@href="'.route($route).'"]')->length);
        }
        $response->assertSee('No applications recorded yet.')->assertSee('No rejected or unprocessed applications.');
    }

    public function test_admin_shell_remains_available_on_user_management_and_settings(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        foreach (['admin.users', 'admin.settings', 'admin.user-activities'] as $route) {
            $this->get(route($route))->assertOk()->assertSee('Admin navigation')->assertSee('admin-profile-menu');
        }
    }

    public function test_guest_and_regular_user_cannot_access_admin_dashboard(): void
    {
        $this->get(route('admin.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.index'))->assertForbidden();
    }
}
