<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_navigation_and_account_tools_are_directly_available_in_sidebar(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get(route('admin.index'))->assertOk();
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $this->assertSame(1, $xpath->query('//*[@id="admin-sidebar"]')->length);
        $this->assertSame(0, $xpath->query('//*[@id="admin-profile-menu"]')->length);
        foreach (['admin.index', 'admin.users', 'admin.user-activities', 'admin.analytics',
            'admin.email-blast', 'admin.feedbacks.index', 'admin.survey.index',
            'admin.settings', 'admin.payments', 'profile.edit'] as $route) {
            $this->assertSame(1, $xpath->query('//*[@id="admin-sidebar"]//a[@href="'.route($route).'"][not(ancestor::div[@class="ac-sidebar-brand"])]')->length, $route);
        }
        $this->assertSame(1, $xpath->query('//*[@id="admin-sidebar"]//a[@aria-current="page"]')->length);
        $this->assertSame(route('admin.index'), $xpath->query('//*[@id="admin-sidebar"]//a[@aria-current="page"]')->item(0)->getAttribute('href'));
        $this->assertSame(1, $xpath->query('//*[@id="admin-sidebar"]//form[@action="'.route('logout').'"]')->length);
        $this->assertSame(0, $xpath->query('//div[contains(@class,"workspace-desktop-links")]')->length);
        $response->assertSee('No applications recorded yet.')->assertSee('No rejected or unprocessed applications.');
    }

    public function test_admin_shell_remains_available_on_user_management_and_settings(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        foreach (['admin.users', 'admin.settings', 'admin.user-activities'] as $route) {
            $this->get(route($route))->assertOk()->assertSee('Admin navigation')->assertSee('id="admin-sidebar"', false)->assertDontSee('id="admin-profile-menu"', false);
        }
    }

    public function test_dashboard_uses_member_avatar_in_applications_and_rankings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['logo' => 'https://example.test/member-photo.jpg']);
        JobApplication::factory()->create(['user_id' => $member->id, 'application_status' => 'Rejected']);

        $response = $this->actingAs($admin)->get(route('admin.index'))->assertOk();
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $this->assertSame(3, $xpath->query('//span[@class="ac-avatar"]/img[@src="https://example.test/member-photo.jpg"]')->length);
        $response->assertSee('Search recent applications')->assertSee($member->name);
    }

    public function test_guest_and_regular_user_cannot_access_admin_dashboard(): void
    {
        $this->get(route('admin.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.index'))->assertForbidden();
    }
}
