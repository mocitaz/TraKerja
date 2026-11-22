<?php

namespace Tests\Feature;

use App\JobEngine\Personalization\NaraJobFinder;
use App\Livewire\ExploreJobs;
use App\Models\JobApplication;
use App\Models\User;
use App\Services\KawanKerjaService;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\Concerns\MakesVacancies;
use Tests\TestCase;

/** Nara recommends real open jobs from the TraKerja 2.0 catalogue, never invented ones. */
class NaraJobFinderTest extends TestCase
{
    use MakesVacancies, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->seed(JobEngineReferenceSeeder::class);
    }

    private function finder(): NaraJobFinder
    {
        return app(NaraJobFinder::class);
    }

    public function test_job_requests_are_recognised_with_role_place_and_mode(): void
    {
        $c = $this->finder()->criteria('Carikan loker data analyst di Bandung yang remote dong');
        $this->assertSame(['data', 'analyst'], $c['terms']);
        $this->assertSame('Bandung', $c['region']['label'], 'Kota and Kabupaten Bandung together');
        $this->assertTrue($c['remote']);

        $this->assertTrue($this->finder()->criteria('ada lowongan magang di jogja?')['internship']);
        $this->assertSame([], $this->finder()->criteria('rekomendasi loker buat aku')['terms']);

        $this->assertNull($this->finder()->criteria('Review my tracked application pipeline and suggest next steps.'));
        $this->assertNull($this->finder()->criteria('Help me prepare for my nearest upcoming interview.'));
    }

    public function test_search_returns_open_jobs_matching_role_and_place_only(): void
    {
        $user = User::factory()->create();
        $bandung = $this->makeVacancy(['title' => 'Data Analyst', 'region' => 'Kota Bandung', 'company' => 'Maju Jaya']);
        $this->makeVacancy(['title' => 'Data Analyst', 'region' => 'Kota Surabaya', 'company' => 'Other']);
        $this->makeVacancy(['title' => 'Barista', 'region' => 'Kota Bandung']);
        $this->makeVacancy(['title' => 'Data Analyst Closed', 'region' => 'Kota Bandung', 'status' => 'closed']);
        $this->makeVacancy(['title' => 'Digital Marketing', 'region' => 'Kota Bandung']); // "it" must not match "Digital"

        $found = $this->finder()->find($user, $this->finder()->criteria('carikan loker data analyst di bandung'));
        $this->assertSame([$bandung->id], array_column($found['jobs'], 'id'));
        $this->assertSame(1, $found['total']);

        $none = $this->finder()->find($user, $this->finder()->criteria('loker IT di bandung'));
        $this->assertSame([], $none['jobs']);
    }

    public function test_a_general_request_uses_the_profile_and_applications(): void
    {
        $user = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $user->id, 'position' => 'Quality Assurance Engineer']);
        $qa = $this->makeVacancy(['title' => 'Quality Assurance Engineer']);
        $this->makeVacancy(['title' => 'Chef']);

        $found = $this->finder()->find($user, $this->finder()->criteria('rekomendasi loker buat aku'));
        $this->assertTrue($found['personal']);
        $this->assertSame($qa->id, $found['jobs'][0]['id']);
    }

    public function test_only_offered_jobs_become_cards(): void
    {
        $job = $this->makeVacancy(['title' => 'Data | Analyst', 'company' => 'Maju Jaya', 'region' => 'Kota Bandung']);
        $other = $this->makeVacancy(['title' => 'Not offered']);

        $reply = $this->finder()->expand("Cocok untuk skill SQL kamu.\n[job:{$job->id}]\n[job:{$other->id}]\n[job:99999]", [$job->id]);

        $this->assertStringContainsString("[job:{$job->id}|Data / Analyst|Maju Jaya|", $reply, 'pipes in a title cannot break the tag');
        $this->assertStringContainsString('/explore?job='.$job->id.'|0|]', $reply, 'no logo: the last field stays empty');
        $this->assertStringNotContainsString('Not offered', $reply);
        $this->assertStringNotContainsString('99999', $reply);
    }

    public function test_nara_gets_the_catalogue_and_replies_with_real_job_cards(): void
    {
        config(['services.openai.nara_tools' => false]); // the fallback flow: keyword pre-search, no tools
        config(['services.openai.key' => 'fake-test-key']);
        $user = User::factory()->create();
        $job = $this->makeVacancy(['title' => 'Data Analyst', 'region' => 'Kota Bandung', 'company' => 'Maju Jaya']);
        Http::fake(['https://api.openai.com/*' => Http::response(['choices' => [['message' => ['role' => 'assistant', 'content' => "Ini loker yang cocok dengan SQL kamu:\n\n[job:{$job->id}]\n\n[job:424242]"], 'finish_reason' => 'stop']]])]);

        $result = (new KawanKerjaService)->chat('carikan loker data analyst di bandung', $user);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString("[job:{$job->id}|Data Analyst|Maju Jaya|", $result['reply']);
        $this->assertStringNotContainsString('424242', $result['reply'], 'an invented id is dropped');
        Http::assertSent(function (Request $request) use ($job) {
            $prompt = $request['messages'][0]['content'] ?? '';

            return str_contains($prompt, 'KATALOG LOKER TRAKERJA (hasil pencarian nyata')
                && str_contains($prompt, '"id":'.$job->id) && str_contains($prompt, 'open_jobs_in_catalogue');
        });
    }

    public function test_a_card_link_opens_the_job_in_explore(): void
    {
        \App\Models\Setting::set('explore_maintenance', false);
        $job = $this->makeVacancy(['title' => 'Data Analyst']);

        Livewire::withQueryParams(['job' => $job->id])->actingAs(User::factory()->create())->test(ExploreJobs::class)
            ->assertSet('previewJobId', $job->id);
    }

    public function test_follow_ups_keep_the_previous_filters(): void
    {
        $previous = $this->finder()->criteria('carikan loker data analyst di bandung');

        $remote = $this->finder()->refine('yang remote aja', $previous);
        $this->assertSame(['data', 'analyst'], $remote['terms']);
        $this->assertSame('Bandung', $remote['region']['label']);
        $this->assertTrue($remote['remote']);

        $surabaya = $this->finder()->refine('di surabaya gimana?', $previous);
        $this->assertSame(['data', 'analyst'], $surabaya['terms']);
        $this->assertStringContainsString('Surabaya', $surabaya['region']['label']);

        $this->assertSame(['marketing'], $this->finder()->refine('kalau marketing?', $previous)['terms']);
        $this->assertTrue($this->finder()->refine('ada yang lain?', $previous)['more']);
        $this->assertNull($this->finder()->refine('makasih ya, membantu banget', $previous));
    }

    public function test_asking_for_more_skips_jobs_already_shown(): void
    {
        $user = User::factory()->create();
        $ids = collect(range(1, 7))->map(fn ($i) => $this->makeVacancy(['title' => "Data Analyst {$i}", 'region' => 'Kota Bandung', 'company' => "Co {$i}"])->id);
        $service = new KawanKerjaService;

        $first = array_column($service->jobSearch($user, 'carikan loker data analyst di bandung')['jobs'], 'id');
        $more = $service->jobSearch($user, 'ada yang lain?');

        $this->assertCount(5, $first);
        $this->assertTrue($more['follow_up']);
        $this->assertSame([], array_intersect($first, array_column($more['jobs'], 'id')));
        $this->assertCount(2, $more['jobs']);
        $this->assertEqualsCanonicalizing($ids->all(), [...$first, ...array_column($more['jobs'], 'id')]);
    }

    public function test_cards_save_jobs_and_searches_from_the_chat(): void
    {
        $user = User::factory()->create();
        $job = $this->makeVacancy(['title' => 'Data Analyst', 'region' => 'Kota Bandung']);
        $closed = $this->makeVacancy(['title' => 'Old', 'status' => 'closed']);

        $this->actingAs($user)->postJson(route('kawankerja.save-search'))->assertStatus(422); // nothing searched yet

        $result = (new KawanKerjaService)->jobSearch($user, 'carikan loker data analyst di bandung');
        $this->assertTrue($result['can_save_search']);
        $reply = (new KawanKerjaService)->withJobCards("[job:{$job->id}]", $result, $user);
        $this->assertStringEndsWith('|0|]'."\n\n".'[savesearch:data analyst · Bandung]', $reply);

        $this->actingAs($user)->postJson(route('kawankerja.save-job'), ['vacancy_id' => $job->id])->assertOk()->assertJson(['saved' => true]);
        $this->actingAs($user)->postJson(route('kawankerja.save-job'), ['vacancy_id' => $job->id])->assertOk(); // idempotent
        $this->assertSame(1, \App\Models\JobBookmark::where('user_id', $user->id)->where('vacancy_id', $job->id)->count());
        $this->actingAs($user)->postJson(route('kawankerja.save-job'), ['vacancy_id' => $closed->id])->assertNotFound();
        $this->assertStringEndsWith('|1|]', (new KawanKerjaService)->withJobCards("[job:{$job->id}]", ['jobs' => [['id' => $job->id]]], $user), 'saved jobs render as saved');

        $this->actingAs($user)->postJson(route('kawankerja.save-search'))->assertOk();
        $search = \App\Models\SavedJobSearch::where('user_id', $user->id)->sole();
        $this->assertSame('email', $search->alert_channel);
        $this->assertSame(['search' => 'data analyst', 'selectedLocation' => 'Kota Bandung'], $search->filters);
        $this->assertFalse((new KawanKerjaService)->jobSearch($user, 'carikan loker data analyst di bandung')['can_save_search'], 'not offered again');
    }

    public function test_the_stream_ends_with_the_reply_that_carries_job_cards(): void
    {
        config(['services.openai.nara_tools' => false]); // the fallback flow: keyword pre-search, no tools
        config(['services.openai.key' => 'fake-test-key']);
        $user = User::factory()->create();
        $job = $this->makeVacancy(['title' => 'Data Analyst', 'region' => 'Kota Bandung', 'company' => 'Maju Jaya']);
        $sse = fn (string $text) => 'data: '.json_encode(['choices' => [['index' => 0, 'delta' => ['content' => $text], 'finish_reason' => null]]])."\n\n";
        Http::fake(['https://api.openai.com/*' => Http::response($sse('Ini pilihannya:').$sse("\n\n[job:{$job->id}]"), 200, ['Content-Type' => 'text/event-stream'])]);

        $body = $this->actingAs($user)->post(route('kawankerja.stream'), ['message' => 'carikan loker data analyst di bandung'])->assertOk()->streamedContent();

        $this->assertStringContainsString('"chunk":"Ini pilihannya:"', $body);
        $this->assertMatchesRegularExpression('/"final":"Ini pilihannya:\\\\n\\\\n\[job:'.$job->id.'\|Data Analyst\|Maju Jaya\|/', $body);
        $this->assertStringEndsWith("data: [DONE]\n\n", $body);
        $saved = \App\Models\NaraMessage::where('user_id', $user->id)->where('role', 'model')->sole();
        $this->assertStringContainsString("[job:{$job->id}|Data Analyst|", $saved->content, 'history keeps the card');
        $this->assertTrue($saved->has_jobs);
        $this->assertStringContainsString('"id":'.$saved->id, $body, 'the client gets the id for 👍 / 👎');
    }
}
