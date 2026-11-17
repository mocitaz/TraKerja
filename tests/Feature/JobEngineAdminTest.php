<?php

namespace Tests\Feature;

use App\JobEngine\Ingest\IngestService;
use App\JobEngine\ScrapedJob;
use App\Jobs\DiscoverSourceJob;
use App\Livewire\Admin\JobEngine;
use App\Models\JobSource;
use App\Models\User;
use App\Models\Vacancy;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class JobEngineAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JobEngineReferenceSeeder::class);
        Http::fake();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_admin' => true]);
    }

    private function pendingPair(): int
    {
        $ingest = app(IngestService::class);
        $noCity = ['city' => null, 'locationText' => null, 'company' => 'Kredivo', 'companyDomain' => 'kredivo.com'];
        $ingest->ingest(JobSource::where('slug', 'dealls')->first(), new ScrapedJob(...['source' => 'dealls', 'url' => 'https://d/1', 'externalId' => 'D1', 'title' => 'Customer Service'] + $noCity));
        $ingest->ingest(JobSource::where('slug', 'kalibrr')->first(), new ScrapedJob(...['source' => 'kalibrr', 'url' => 'https://k/1', 'externalId' => 'K1', 'title' => 'Customer Service Officer'] + $noCity));

        return (int) DB::table('dedup_reviews')->where('status', 'pending')->value('id');
    }

    public function test_only_admins_can_open_it(): void
    {
        $this->actingAs(User::factory()->create())->get(route('admin.job-engine'))->assertForbidden();
        $this->actingAs($this->admin())->get(route('admin.job-engine'))->assertOk()->assertSee('Mesin Data Loker');
    }

    public function test_reviewer_merges_or_separates_a_pending_pair(): void
    {
        $reviewId = $this->pendingPair();
        $this->assertSame(2, Vacancy::where('status', 'active')->count());

        Livewire::actingAs($this->admin())->test(JobEngine::class)
            ->set('tab', 'pairs')
            ->assertSee('Customer Service Officer')
            ->call('resolvePair', $reviewId, true);

        $this->assertSame(1, Vacancy::where('status', 'active')->count());
        $this->assertSame('merged_manual', DB::table('dedup_reviews')->where('id', $reviewId)->value('status'));
    }

    public function test_separating_keeps_both_vacancies(): void
    {
        $reviewId = $this->pendingPair();

        Livewire::actingAs($this->admin())->test(JobEngine::class)->call('resolvePair', $reviewId, false);

        $this->assertSame(2, Vacancy::where('status', 'active')->count());
        $this->assertSame('separate', DB::table('dedup_reviews')->where('id', $reviewId)->value('status'));
    }

    public function test_weekly_qa_sample_and_verdicts(): void
    {
        $this->pendingPair();
        $component = Livewire::actingAs($this->admin())->test(JobEngine::class)->set('tab', 'qa')->call('drawQaSample');

        $check = DB::table('vacancy_qa_checks')->first();
        $this->assertSame(now()->format('o-\WW'), $check->sample);
        $this->assertSame(2, DB::table('vacancy_qa_checks')->count());

        $component->set("qaNotes.{$check->id}", 'Lokasi salah')->call('judge', $check->id, 'wrong_data');
        $this->assertSame(['wrong_data', 'Lokasi salah'], [DB::table('vacancy_qa_checks')->where('id', $check->id)->value('verdict'), DB::table('vacancy_qa_checks')->where('id', $check->id)->value('note')]);
    }

    public function test_run_now_queues_discovery_and_toggle_switches_a_source(): void
    {
        Queue::fake();
        $source = JobSource::where('slug', 'pintarnya')->first();

        Livewire::actingAs($this->admin())->test(JobEngine::class)
            ->call('runNow', $source->id)
            ->call('toggle', $source->id);

        Queue::assertPushed(DiscoverSourceJob::class, fn ($job) => $job->sourceId === $source->id);
        $this->assertFalse($source->fresh()->is_active);
    }
}
