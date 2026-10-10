<?php

namespace Tests\Feature;

use App\Livewire\Admin\UserManagement;
use App\Livewire\JobKanbanBoard;
use App\Livewire\JobTableList;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BoundedPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tampered_table_and_admin_sizes_are_bounded(): void
    {
        $owner = User::factory()->create();
        JobApplication::factory()->count(105)->create(['user_id' => $owner->id, 'application_date' => now()]);
        foreach ([1000000, 'all', 0, -1, 'bad'] as $size) {
            Livewire::actingAs($owner)->test(JobTableList::class)->set('perPage', $size)
                ->assertViewHas('jobApplications', fn ($rows) => $rows->perPage() === 30 && $rows->count() === 30);
        }
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(105)->create();
        foreach ([1000000, 'all', 0, -1, 'bad'] as $size) {
            Livewire::actingAs($admin)->test(UserManagement::class)->set('perPage', $size)
                ->assertViewHas('users', fn ($rows) => $rows->perPage() === 10 && $rows->count() === 10);
        }
        Livewire::actingAs($owner)->test(JobTableList::class)->set('perPage', 100)->assertViewHas('jobApplications', fn ($rows) => $rows->count() === 100);
    }

    public function test_kanban_loads_a_page_per_column_with_stable_order_and_owner_scope(): void
    {
        $owner = User::factory()->create();
        JobApplication::factory()->count(35)->create(['user_id' => $owner->id, 'application_status' => 'On Process', 'application_date' => now(), 'career_level' => 'Full Time']);
        JobApplication::factory()->create(['user_id' => User::factory()->create()->id, 'company_name' => 'Foreign owner']);
        $board = Livewire::actingAs($owner)->test(JobKanbanBoard::class)
            ->assertViewHas('statuses', fn ($statuses) => $statuses[0]->jobApplications->count() === 30 && $statuses[0]->total === 35)
            ->assertDontSee('Foreign owner');
        $firstIds = $board->viewData('statuses')[0]->jobApplications->pluck('id')->all();
        $board->call('nextPage', 'column_on_process')->assertViewHas('statuses', fn ($statuses) => $statuses[0]->jobApplications->count() === 5);
        $this->assertSame([], array_intersect($firstIds, $board->viewData('statuses')[0]->jobApplications->pluck('id')->all()));
        $board->set('search', 'not found')->assertViewHas('statuses', fn ($statuses) => $statuses[0]->total === 0);
        $board->set('search', '')->set('careerLevelFilter', 'Intern')->assertViewHas('statuses', fn ($statuses) => $statuses[0]->total === 0);
    }
}
