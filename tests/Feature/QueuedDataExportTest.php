<?php

namespace Tests\Feature;

use App\Jobs\BuildDataExport;
use App\Livewire\Admin\UserManagement;
use App\Models\DataExport;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class QueuedDataExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        Storage::fake('local');
    }

    public function test_large_tracker_export_queues_private_file_with_same_csv_schema(): void
    {
        $owner = User::factory()->create();
        JobApplication::factory()->count(501)->create(['user_id' => $owner->id, 'company_name' => '=Example', 'application_date' => now()]);
        JobApplication::factory()->create(['company_name' => 'Other owner']);
        $this->actingAs($owner)->get('/csv/export')->assertRedirect('/data-exports/1');
        $this->get('/csv/export')->assertRedirect('/data-exports/1');
        $export = DataExport::firstOrFail();
        $this->assertSame('queued', $export->status);
        Queue::assertPushed(BuildDataExport::class, 1);
        (new BuildDataExport($export->id))->handle();
        (new BuildDataExport($export->id))->handle();
        $export->refresh();
        $this->assertSame('ready', $export->status);
        $this->assertSame(501, $export->row_count);
        $csv = Storage::disk('local')->get($export->path);
        $this->assertStringStartsWith("\xEF\xBB\xBF\"Company Name\",", $csv);
        $this->assertStringContainsString("'=Example", $csv);
        $this->assertStringContainsString('trakerja-v1', $csv);
        $this->assertStringNotContainsString('Other owner', $csv);
        (new BuildDataExport($export->id))->failed(new \RuntimeException('late duplicate failure'));
        Storage::disk('local')->assertExists($export->path);
        $this->get('/data-exports/'.$export->id.'/download')->assertOk()->assertHeader('Cache-Control', 'no-store, private');
        $this->actingAs(User::factory()->create())->get('/data-exports/'.$export->id.'/download')->assertNotFound();
    }

    public function test_large_admin_export_preserves_filters_and_rechecks_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(501)->create(['name' => 'Matching user', 'is_premium' => true]);
        User::factory()->create(['name' => 'Excluded user', 'is_premium' => false]);
        Livewire::actingAs($admin)->test(UserManagement::class)->set('filterPremium', 'premium')->call('exportUsers')->assertRedirect('/data-exports/1');
        $export = DataExport::firstOrFail();
        (new BuildDataExport($export->id))->handle();
        $csv = Storage::disk('local')->get($export->fresh()->path);
        $this->assertStringContainsString('Matching user', $csv);
        $this->assertStringNotContainsString('Excluded user', $csv);
        $this->assertSame(501, $export->fresh()->row_count);
        $admin->update(['role' => 'user']);
        $this->actingAs($admin->fresh())->get('/data-exports/'.$export->id.'/download')->assertForbidden();
        Livewire::actingAs(User::factory()->create())->test(UserManagement::class)->call('exportUsers')->assertForbidden();
    }

    public function test_expired_or_failed_export_is_not_downloadable_and_cleanup_removes_file(): void
    {
        $user = User::factory()->create();
        $export = DataExport::create(['user_id' => $user->id, 'kind' => 'jobs', 'status' => 'queued', 'filters' => [], 'max_source_id' => 0, 'expires_at' => now()->addDay()]);
        $this->actingAs($user)->get('/data-exports/'.$export->id.'/download')->assertStatus(409);
        (new BuildDataExport($export->id))->failed(new \RuntimeException('simulated worker failure'));
        $this->get('/data-exports/'.$export->id)->assertOk()->assertSee('Export failed');
        Storage::disk('local')->put('data-exports/expired.csv', 'private data');
        $export->update(['status' => 'ready', 'path' => 'data-exports/expired.csv', 'expires_at' => now()->subMinute()]);
        $this->get('/data-exports/'.$export->id.'/download')->assertGone();
        $this->artisan('data-exports:cleanup')->assertSuccessful();
        Storage::disk('local')->assertMissing('data-exports/expired.csv');
    }
}
