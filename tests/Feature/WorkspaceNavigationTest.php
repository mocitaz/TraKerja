<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_has_top_navigation_feature_access_and_existing_data(): void
    {
        $user = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $user->id, 'company_name' => 'Workspace IT', 'position' => 'Backend Developer']);
        $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $response->assertSee('id="workspace-navigation"', false)
            ->assertDontSee('id="primary-sidebar"', false)
            ->assertDontSee('id="dashboard-feature-links"', false)
            ->assertSee('Recent applications')->assertSee('Workspace IT')
            ->assertSee('Application activity')->assertSee('Your next move');
        $document = new \DOMDocument;
        libxml_use_internal_errors(true);
        $document->loadHTML($response->getContent());
        libxml_clear_errors();
        $xpath = new \DOMXPath($document);
        $this->assertSame(0, $xpath->query('//*[@id="workspace-navigation"]//a[@href="'.route('support.index').'"]')->length);
        $this->assertSame(1, $xpath->query('//footer//a[@href="'.route('support.index').'"]')->length);
        foreach (['legal.privacy', 'legal.terms', 'cv.builder', 'ai-studio.index'] as $footerRoute) {
            $this->assertSame(1, $xpath->query('//footer//a[@href="'.route($footerRoute).'"]')->length);
        }
        $this->assertSame(4, $xpath->query('//nav[@aria-label="Mobile primary navigation"]/a')->length);
        $this->assertSame(1, $xpath->query('//nav[@aria-label="Mobile primary navigation"]/a[@aria-current="page"]')->length);
        $profile = $xpath->query('//button[@aria-controls="workspace-profile"]')->item(0);
        $this->assertStringContainsString($user->name, $profile->textContent);
        $this->assertStringContainsString($user->email, $profile->textContent);
        $this->assertSame(4, $xpath->query('//*[@id="workspace-navigation"]//div[@class="workspace-desktop-links"]/a')->length);
        foreach (['dashboard', 'tracker', 'jobs.explore', 'summary'] as $route) {
            $this->assertSame(1, $xpath->query('//*[@id="workspace-navigation"]//div[@class="workspace-desktop-links"]/a[@href="'.route($route).'"]')->length);
        }
        foreach (['interviews', 'cv.builder', 'ai-studio.index'] as $route) {
            $this->assertSame(0, $xpath->query('//*[@id="workspace-navigation"]//a[@href="'.route($route).'"]')->length);
            $this->assertGreaterThanOrEqual(1, $xpath->query('//*[@id="dashboard-next-move"]//a[@href="'.route($route).'"]')->length);
        }
        $response->assertSee('aria-controls="workspace-profile"', false)
            ->assertSee('aria-controls="workspace-mobile-menu"', false);
    }

    public function test_empty_dashboard_offers_first_application_and_job_discovery(): void
    {
        $this->actingAs(User::factory()->create())->get(route('dashboard'))->assertOk()
            ->assertSee('No applications yet')->assertSee('Add application')
            ->assertSee('Explore Jobs')->assertDontSee('id="primary-sidebar"', false);
    }

    public function test_recent_cards_show_only_the_three_latest_applications_owned_by_the_user(): void
    {
        $user = User::factory()->create();
        $applications = collect(range(1, 5))->map(fn ($day) => JobApplication::factory()->create([
            'user_id' => $user->id,
            'application_date' => "2026-10-0{$day}",
            'interview_date' => null,
        ]));
        JobApplication::factory()->create(['application_date' => '2026-10-09']);

        $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->assertSame(
            $applications->reverse()->take(3)->pluck('id')->all(),
            $response->viewData('recentApplications')->pluck('id')->all(),
        );
        foreach ($applications->reverse()->take(3) as $application) {
            $response->assertSee(route('jobs.show', $application));
        }
    }

    public function test_overview_uses_owner_total_including_archived_and_keeps_overlapping_metrics(): void
    {
        $user = User::factory()->create();
        foreach ([
            ['application_status' => 'On Process', 'recruitment_stage' => 'Offering'],
            ['application_status' => 'On Process', 'recruitment_stage' => 'Applied'],
            ['application_status' => 'Accepted', 'recruitment_stage' => 'Offering'],
            ['application_status' => 'Rejected', 'recruitment_stage' => 'Applied', 'is_archived' => true],
        ] as $attributes) {
            JobApplication::factory()->create(array_merge([
                'user_id' => $user->id, 'application_date' => now(), 'is_archived' => false,
            ], $attributes));
        }
        JobApplication::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $response->assertViewHas('totalTrackedApplications', 4)
            ->assertViewHas('onProcessCount', 2)
            ->assertViewHas('offeringAcceptedCount', 2)
            ->assertViewHas('declinedCount', 1)
            ->assertSee('Application overview')
            ->assertDontSee('In selection');
    }

    public function test_active_navigation_follows_current_page(): void
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('tracker'))->assertOk();
        $this->assertMatchesRegularExpression('/href="'.preg_quote(route('tracker'), '/').'"[^>]*aria-current="page"/', $response->getContent());
        $response->assertDontSee('id="primary-sidebar"', false);
    }

    public function test_upcoming_interviews_show_only_the_three_nearest_owned_schedules(): void
    {
        $user = User::factory()->create();
        $interviews = collect(range(1, 5))->map(fn ($days) => JobApplication::factory()->create([
            'user_id' => $user->id,
            'interview_date' => now()->addDays($days),
            'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview', 'is_archived' => false,
        ]));
        JobApplication::factory()->create(['interview_date' => now()->addHour()]);
        JobApplication::factory()->create(['user_id' => $user->id, 'application_status' => 'Rejected', 'interview_date' => now()->addHour()]);
        JobApplication::factory()->create(['user_id' => $user->id, 'application_status' => 'On Process', 'is_archived' => true, 'interview_date' => now()->addHour()]);
        JobApplication::factory()->create(['user_id' => $user->id, 'interview_date' => now()->subDay()]);

        $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->assertSame(
            $interviews->take(3)->pluck('id')->all(),
            $response->viewData('upcomingInterviews')->pluck('id')->all(),
        );
    }
}
