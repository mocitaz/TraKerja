<?php

namespace Tests\Feature;

use App\Livewire\JobKanbanBoard;
use App\Livewire\JobTableList;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TrackerWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_filters_work_together_and_clear_without_exposing_other_users(): void
    {
        $owner = User::factory()->create();
        $match = JobApplication::factory()->create(['user_id' => $owner->id, 'company_name' => 'Orbit Software', 'position' => 'Engineer', 'platform' => 'LinkedIn', 'recruitment_stage' => 'HR - Interview', 'application_status' => 'On Process', 'application_date' => now()]);
        JobApplication::factory()->create(['user_id' => $owner->id, 'company_name' => 'Another Company', 'recruitment_stage' => 'Applied', 'application_date' => now()]);
        JobApplication::factory()->create(['company_name' => 'Foreign Company', 'user_id' => User::factory()->create()->id, 'application_date' => now()]);
        $list = Livewire::actingAs($owner)->test(JobTableList::class)
            ->set('search', 'Orbit')->set('platformFilter', 'LinkedIn')->set('recruitmentStageFilter', 'HR - Interview')
            ->assertViewHas('jobApplications', fn ($rows) => $rows->pluck('id')->all() === [$match->id])
            ->assertDontSee('Foreign Company');
        $list->call('clearFilters')->assertViewHas('jobApplications', fn ($rows) => $rows->total() === 2);
    }

    public function test_board_status_action_moves_only_the_owned_card_and_updates_column_counts(): void
    {
        $owner = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'On Process', 'application_date' => now()]);
        $foreign = JobApplication::factory()->create(['user_id' => User::factory()->create()->id, 'application_status' => 'On Process', 'application_date' => now()]);
        $board = Livewire::actingAs($owner)->test(JobKanbanBoard::class)
            ->call('updateStatus', $job->id, 'Accepted')
            ->assertViewHas('statuses', fn ($columns) => $columns[0]->total === 0 && $columns[1]->total === 1)
            ->assertDispatched('status-updated');
        $this->assertSame('Accepted', $job->fresh()->application_status);
        $board->call('updateStatus', $foreign->id, 'Rejected');
        $this->assertSame('On Process', $foreign->fresh()->application_status);
        $board->call('updateStatus', $job->id, 'unknown')->assertHasErrors('newStatus');
        $this->assertSame('Accepted', $job->fresh()->application_status);
    }
    public function test_table_separates_status_stage_and_dates_and_announces_the_actual_sort(): void
    {
        $owner = User::factory()->create();
        $job = JobApplication::factory()->create([
            'user_id' => $owner->id, 'company_name' => 'Readable Company', 'position' => 'Backend Engineer',
            'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview',
            'application_date' => today()->subDays(20), 'interview_date' => now()->addDays(2),
        ]);
        $list = Livewire::actingAs($owner)->test(JobTableList::class)->assertSee('Draft follow-up');
        $document = new \DOMDocument;
        libxml_use_internal_errors(true);
        $document->loadHTML($list->html());
        libxml_clear_errors();
        $xpath = new \DOMXPath($document);
        $cells = $xpath->query('//table/tbody/tr[1]/td');
        $this->assertSame(7, $cells->length);
        $this->assertStringContainsString('On Process', $cells->item(2)->textContent);
        $this->assertStringNotContainsString('HR - Interview', $cells->item(2)->textContent);
        $this->assertStringContainsString('HR - Interview', $cells->item(3)->textContent);
        $this->assertStringContainsString($job->application_date->format('d M Y'), $cells->item(4)->textContent);
        $this->assertStringContainsString($job->interview_date->timezone('Asia/Jakarta')->format('d M Y'), $cells->item(5)->textContent);
        $list->assertSee('aria-sort="descending"', false)->call('sortBy', 'position')->assertSet('sortDirection', 'asc')->assertSee('aria-sort="ascending"', false);
    }

}
