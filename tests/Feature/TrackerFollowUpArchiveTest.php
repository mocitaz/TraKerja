<?php

namespace Tests\Feature;

use App\Livewire\JobKanbanBoard;
use App\Livewire\JobTableList;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class TrackerFollowUpArchiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_follow_up_is_available_only_for_active_unfinished_applications_after_fourteen_days(): void
    {
        $this->freezeTime();
        foreach ([
            ['On Process', 'Applied', false, -14, true],
            ['On Process', 'Applied', false, -13, false],
            ['On Process', 'Applied', false, 20, false],
            ['On Process', 'Not Processed', false, -20, false],
            ['On Process', 'Offering', false, -20, false],
            ['On Process', 'Applied', true, -20, false],
            ['Rejected', 'Applied', false, -20, false],
            ['Accepted', 'Applied', false, -20, false],
        ] as [$status, $stage, $archived, $days, $eligible]) {
            $job = JobApplication::factory()->create([
                'application_status' => $status, 'recruitment_stage' => $stage,
                'is_archived' => $archived, 'application_date' => now()->addDays($days),
            ]);
            $this->assertSame($eligible, $job->isGhosted(), "$status / $stage / $days days");
        }
    }

    public function test_both_views_guard_follow_up_actions_and_generate_owned_eligible_drafts(): void
    {
        $owner = User::factory()->create(['auto_archive_rejected' => false]);
        $eligible = JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'On Process', 'recruitment_stage' => 'Applied', 'application_date' => now()->subDays(20)]);
        $rejected = JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'Rejected', 'application_date' => now()->subDays(20)]);
        $foreign = JobApplication::factory()->create(['application_status' => 'On Process', 'recruitment_stage' => 'Applied', 'application_date' => now()->subDays(20)]);
        foreach ([JobTableList::class, JobKanbanBoard::class] as $component) {
            Http::fake(['*' => Http::response(['result' => 'Draft for the selected application'])]);
            $view = Livewire::actingAs($owner)->test($component);
            $view->call('generateFollowUp', $rejected->id)->assertSet('showFollowUpModal', false);
            $view->call('generateFollowUp', $foreign->id)->assertSet('showFollowUpModal', false);
            Http::assertNothingSent();
            $view->call('generateFollowUp', $eligible->id)->assertSet('showFollowUpModal', true)
                ->assertSet('followUpDraft', 'Draft for the selected application');
            Http::assertSentCount(1);
            $view->call('closeFollowUpModal')->assertSet('followUpDraft', '');
        }
    }

    public function test_auto_archive_respects_owner_preference_age_and_reactivation_in_both_views(): void
    {
        $this->freezeTime();
        foreach ([JobTableList::class, JobKanbanBoard::class] as $component) {
            $owner = User::factory()->create(['auto_archive_rejected' => true]);
            $disabled = User::factory()->create(['auto_archive_rejected' => false]);
            $old = JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'Rejected', 'recruitment_stage' => 'Applied', 'updated_at' => now()->subDays(14)]);
            $recent = JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'Rejected', 'updated_at' => now()->subDays(13)]);
            $notProcessed = JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'On Process', 'recruitment_stage' => 'Not Processed', 'updated_at' => now()->subDays(15)]);
            $optOut = JobApplication::factory()->create(['user_id' => $disabled->id, 'application_status' => 'Rejected', 'updated_at' => now()->subDays(20)]);
            $view = Livewire::actingAs($owner)->test($component);
            $this->assertTrue($old->fresh()->is_archived);
            $this->assertTrue($notProcessed->fresh()->is_archived);
            $this->assertFalse($recent->fresh()->is_archived);
            $this->assertFalse($optOut->fresh()->is_archived);
            $view->call('toggleArchived')->assertSet('showArchived', true)->assertViewHas('archivedCount', 2);
            if ($component === JobKanbanBoard::class) {
                $view->call('updateStatus', $old->id, 'On Process');
            } else {
                $old->refresh()->update(['application_status' => 'On Process', 'recruitment_stage' => 'Applied']);
            }
            $this->assertFalse($old->fresh()->is_archived);
            $this->assertNull($old->fresh()->archived_at);
            JobApplication::autoArchiveUserJobs($disabled->id);
            $this->assertFalse($optOut->fresh()->is_archived);
        }
    }
}
