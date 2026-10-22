<?php

namespace Tests\Feature;

use App\Livewire\CareerSummaryPro;
use App\Models\JobApplication;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SqliteAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_ranks_only_users_with_rejected_or_not_processed_applications(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $first = User::factory()->create();
        $second = User::factory()->create();
        $excluded = User::factory()->create();
        foreach ([$first, $second, $excluded, $admin] as $user) {
            JobApplication::factory()->create([
                'user_id' => $user->id, 'application_status' => 'On Process', 'recruitment_stage' => 'Applied',
            ]);
        }
        JobApplication::factory()->create([
            'user_id' => $first->id, 'application_status' => 'Rejected', 'recruitment_stage' => 'Not Processed',
        ]);
        JobApplication::factory()->create([
            'user_id' => $first->id, 'application_status' => 'On Process', 'recruitment_stage' => 'Not Processed',
        ]);
        foreach ([$second, $admin] as $user) {
            JobApplication::factory()->create([
                'user_id' => $user->id, 'application_status' => 'Rejected', 'recruitment_stage' => 'Applied',
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.index'))->assertOk();
        $ranking = explode('</section>', explode('data-admin-rejection-ranking', $response->getContent(), 2)[1], 2)[0];
        $this->assertStringContainsString($first->email, $ranking);
        $this->assertStringContainsString($second->email, $ranking);
        $this->assertStringNotContainsString($excluded->email, $ranking);
        $this->assertStringNotContainsString($admin->email, $ranking);
        $this->assertMatchesRegularExpression('/2\s*<small>(?:fail|unplaced)/', $ranking);
        $this->assertMatchesRegularExpression('/1\s*<small>(?:fail|unplaced)/', $ranking);
    }

    public function test_summary_renders_each_filter_with_owner_scoped_timeline_counts(): void
    {
        $this->travelTo(now()->setDate(2026, 10, 9)->setTime(12, 0));
        $user = User::factory()->create();
        JobApplication::factory()->count(2)->create([
            'user_id' => $user->id,
            'application_date' => '2026-10-08',
            'interview_date' => '2026-10-09 10:00:00',
        ]);
        JobApplication::factory()->create(['user_id' => $user->id, 'application_date' => '2026-09-01', 'interview_date' => null]);
        JobApplication::factory()->create(['application_date' => '2026-10-08', 'interview_date' => '2026-10-09 10:00:00']);

        foreach (['all' => 3, 'monthly' => 2, 'weekly' => 2] as $filter => $count) {
            $this->actingAs($user)->get(route('summary', ['timeFilter' => $filter]))
                ->assertOk()->assertViewHas('applicationsCount', $count)
                ->assertViewHas('timelineData', fn ($data) => $data['applications']->sum() === $count && $data['interviews']->sum() === 2);
        }
    }

    public function test_career_month_buckets_preserve_year_and_owner(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        foreach (['2025-10-08', '2026-10-08'] as $date) {
            JobApplication::factory()->create(['user_id' => $user->id, 'application_date' => $date, 'recruitment_stage' => 'HR - Interview']);
        }
        JobApplication::factory()->create(['application_date' => '2026-10-08']);
        $component = new CareerSummaryPro;
        $component->timeFilter = 'all';
        $timeline = $component->getTimelineDataProperty();
        $this->assertSame(['2025-10' => 1, '2026-10' => 1], $timeline['applications']->pluck('applications', 'period')->all());
        $this->assertSame(2, $timeline['interviews']->sum('interviews'));
    }

    public function test_admin_analytics_renders_with_day_hour_and_resolution_aggregates(): void
    {
        $this->travelTo(now()->setDate(2026, 10, 9)->setTime(16, 0));
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);
        $user = User::factory()->create();
        $ticket = new SupportTicket;
        $ticket->forceFill([
            'user_id' => $user->id, 'category' => 'technical_issue', 'subject' => 'Dummy issue',
            'message' => 'Dummy message', 'status' => 'replied',
            'created_at' => '2026-10-09 08:00:00', 'replied_at' => '2026-10-09 10:59:00',
        ])->save();
        $activity = new UserActivity;
        $activity->forceFill([
            'user_id' => $user->id, 'activity_type' => 'job_add', 'description' => 'Dummy application',
            'status' => 'success', 'created_at' => '2026-10-09 15:00:00',
        ])->save();

        $this->actingAs($admin)->get(route('admin.analytics'))->assertOk();
        $component = new \App\Livewire\Admin\Analytics;
        $stats = (new \ReflectionMethod($component, 'fetchSupportTicketStats'))->invoke($component);
        $this->assertSame(3.0, $stats['avg_resolution_hrs']);
        $hours = (new \ReflectionMethod($component, 'fetchPeakActivityHours'))->invoke($component);
        $this->assertSame(1, $hours['data'][15]);
    }
}
