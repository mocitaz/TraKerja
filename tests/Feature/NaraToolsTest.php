<?php

namespace Tests\Feature;

use App\JobEngine\Personalization\NaraJobFinder;
use App\Models\JobBookmark;
use App\Models\NaraMemory;
use App\Models\SavedJobSearch;
use App\Models\User;
use App\Models\UserSkill;
use App\Services\KawanKerjaService;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\FakesOpenAi;
use Tests\Concerns\MakesVacancies;
use Tests\TestCase;

/** Nara with tools: the model decides when to search the catalogue and with which filters; the server runs the tools. */
class NaraToolsTest extends TestCase
{
    use FakesOpenAi, MakesVacancies, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->seed(JobEngineReferenceSeeder::class);
        config(['services.openai.key' => 'fake-test-key', 'services.openai.strong_model' => null]);
    }

    /** A model reply that asks for a tool. */
    private function toolCall(string $name, array $args): array
    {
        return $this->openAiToolCall($name, $args, 'call_'.$name);
    }

    private function text(string $text): array
    {
        return $this->openAiText($text);
    }

    /** The model answers with these replies, one per request, and every request is kept for inspection. */
    private function script(array ...$replies): void
    {
        $this->openAi(...$replies);
    }

    /** The tool result Nara sent back to the model in request number $n (0-based): the last one. */
    private function toolResult(int $n): array
    {
        return collect(self::toolResults(Http::recorded()[$n][0]))->last();
    }

    public function test_nara_searches_the_catalogue_with_filters_it_chose_itself(): void
    {
        $user = User::factory()->create();
        $dev = $this->makeVacancy(['title' => 'Web Developer', 'region' => 'Kota Bandung', 'company' => 'Maju Jaya', 'work_mode' => 'remote']);
        $this->makeVacancy(['title' => 'Backend Engineer', 'region' => 'Kota Bandung', 'company' => 'Other', 'work_mode' => 'onsite']);
        $this->makeVacancy(['title' => 'Barista', 'region' => 'Kota Bandung']);
        // The user wrote "programer web" (a typo and Indonesian); Nara turned it into related English titles.
        $this->script(
            $this->toolCall('search_jobs', ['keywords' => ['web developer', 'frontend', 'backend engineer'], 'location' => 'Bandung', 'work_mode' => 'remote']),
            $this->text("Ini yang cocok:\n\n[job:{$dev->id}]\nCocok untuk pengalaman web kamu."),
        );

        $result = (new KawanKerjaService)->chat('carikan loker programer web di bandung yg remote', $user);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString("[job:{$dev->id}|Web Developer|Maju Jaya|", $result['reply'], 'the tag became a card');
        $found = $this->toolResult(1);
        $this->assertSame([$dev->id], array_column($found['jobs'], 'id'), 'remote filter applied, Barista and the onsite job left out');
        $this->assertSame(1, $found['total_matching']);
        // The assistant turn that asked for the tool went back before its result, so the conversation stays valid.
        $second = Http::recorded()[1][0];
        $asked = collect($second['messages'])->firstWhere('role', 'assistant');
        $this->assertSame('call_search_jobs', $asked['tool_calls'][0]['id']);
        $this->assertSame('call_search_jobs', collect($second['messages'])->firstWhere('role', 'tool')['tool_call_id']);
        $this->assertSame('Bearer fake-test-key', $second->header('Authorization')[0] ?? null, 'the key travels in a header');
        $this->assertStringNotContainsString('fake-test-key', $second->url(), 'never in the URL');
        $this->assertSame('function', $second['tools'][0]['type']);
        $this->assertSame('object', $second['tools'][0]['function']['parameters']['type'], 'schema types are JSON schema (lower case)');
    }

    public function test_structured_filters_level_salary_deadline_company_and_more(): void
    {
        $user = User::factory()->create();
        $finder = app(NaraJobFinder::class);
        $senior = $this->makeVacancy(['title' => 'Sales Lead', 'seniority' => 'senior', 'salary_min' => 9_000_000, 'salary_max' => 12_000_000, 'salary_currency' => 'IDR', 'salary_period' => 'month', 'valid_through' => now()->addDays(3), 'company' => 'Acme']);
        $entry = $this->makeVacancy(['title' => 'Sales Executive', 'seniority' => 'entry', 'salary_min' => 4_000_000, 'salary_max' => 5_000_000, 'salary_currency' => 'IDR', 'salary_period' => 'month', 'valid_through' => now()->addDays(20), 'employment_type' => 'part_time']);
        $this->makeVacancy(['title' => 'Sales Associate', 'seniority' => 'entry']); // states no salary

        $ids = fn (array $args) => array_column($finder->search($user, $args + ['keywords' => ['sales']])['jobs'], 'id');
        $this->assertEqualsCanonicalizing([$senior->id, $entry->id], array_intersect($ids([]), [$senior->id, $entry->id]));
        $this->assertCount(3, $ids([]));
        $this->assertSame([$senior->id], $ids(['min_monthly_salary_idr' => 8_000_000]), 'only jobs that state a salary can match');
        $this->assertSame([$entry->id], array_values(array_diff($ids(['level' => 'entry', 'employment_type' => 'part_time']), [])));
        $this->assertSame([$senior->id], $ids(['closing_within_days' => 7]));
        $this->assertSame([$senior->id], $ids(['company' => 'acme']));

        // A salary nobody states: the search is repeated without that filter, and Nara is told.
        $none = $finder->search($user, ['keywords' => ['sales'], 'min_monthly_salary_idr' => 50_000_000]);
        $this->assertTrue($none['salary_filter_dropped']);
        $this->assertNotEmpty($none['jobs']);
        $this->assertStringContainsString('WITHOUT the salary filter', implode(' ', $none['notes']));

        $first = array_column($finder->search($user, ['keywords' => ['sales']])['jobs'], 'id');
        $this->assertSame([], array_intersect($first, array_column($finder->search($user, ['keywords' => ['sales']], $first)['jobs'], 'id')), 'only_new skips shown jobs');
        $this->assertStringContainsString('location_not_recognized', implode(' ', $finder->search($user, ['keywords' => ['sales'], 'location' => 'Atlantis'])['notes']));
    }

    public function test_job_cards_come_with_the_skills_the_user_has_and_lacks(): void
    {
        $user = User::factory()->create(['is_premium' => true, 'payment_status' => 'paid', 'premium_until' => now()->addYear()]);
        UserSkill::create(['user_id' => $user->id, 'skill_name' => 'React', 'display_order' => 1]);
        $job = $this->makeVacancy(['title' => 'Frontend Engineer', 'skills' => ['React', 'GraphQL']]);
        $scorer = \Mockery::mock(\App\JobEngine\Personalization\MatchScorer::class);
        $scorer->shouldReceive('rankedIds')->andReturn([$job->id]);
        $scorer->shouldReceive('forVacancies')->andReturn([$job->id => ['score' => 72, 'matched' => ['react'], 'missing' => ['graphql'], 'level_gap' => false, 'label' => 'good']]);
        $this->app->instance(\App\JobEngine\Personalization\MatchScorer::class, $scorer);

        $found = app(NaraJobFinder::class)->search($user, ['keywords' => ['frontend']])['jobs'][0];

        $this->assertSame(72, $found['cv_match_percent']);
        $this->assertSame(['react'], $found['skills_you_have']);
        $this->assertSame(['graphql'], $found['skills_you_lack']);
    }

    public function test_nara_saves_a_job_and_sets_an_alert_only_through_its_tools(): void
    {
        $user = User::factory()->create();
        $job = $this->makeVacancy(['title' => 'Data Analyst', 'region' => 'Kota Bandung']);
        $this->script(
            $this->toolCall('search_jobs', ['keywords' => ['data analyst'], 'location' => 'Bandung']),
            $this->toolCall('save_job', ['job_id' => $job->id]),
            $this->toolCall('create_job_alert', ['name' => 'Data Bandung']),
            $this->text("Sudah kusimpan dan alert-nya aktif.\n\n[job:{$job->id}]"),
        );

        $result = (new KawanKerjaService)->chat('cari data analyst di bandung, simpan yang pertama, dan kabari kalau ada baru', $user);

        $this->assertTrue(JobBookmark::where('user_id', $user->id)->where('vacancy_id', $job->id)->exists());
        $alert = SavedJobSearch::where('user_id', $user->id)->sole();
        $this->assertSame('Data Bandung', $alert->name);
        $this->assertSame('email', $alert->alert_channel);
        $this->assertSame(['search' => 'data analyst', 'selectedLocation' => 'Kota Bandung'], $alert->filters);
        $this->assertStringEndsWith('|1|]', $result['reply'], 'the card shows the job as saved');
        $this->assertStringNotContainsString('[savesearch:', $result['reply'], 'already saved: no second offer');
    }

    public function test_an_invented_job_id_never_becomes_a_card_and_a_broken_tool_does_not_break_the_chat(): void
    {
        $user = User::factory()->create();
        $job = $this->makeVacancy(['title' => 'Data Analyst']);
        $this->script(
            $this->toolCall('get_job_details', ['job_id' => 999999]),
            $this->toolCall('definitely_not_a_tool', []),
            $this->text("Maaf, aku tidak menemukannya.\n\n[job:999999]\n[job:{$job->id}]"),
        );

        $result = (new KawanKerjaService)->chat('ceritakan loker nomor 999999', $user);

        $this->assertTrue($result['success']);
        $this->assertSame('job not found', $this->toolResult(1)['error']);
        $this->assertStringContainsString('unknown tool', $this->toolResult(2)['error']);
        $this->assertStringNotContainsString('999999', $result['reply']);
        $this->assertStringNotContainsString('[job:'.$job->id, $result['reply'], 'a job that was never offered this turn is not shown either');
    }

    public function test_nara_compares_jobs_and_remembers_and_forgets_preferences(): void
    {
        $user = User::factory()->create();
        $a = $this->makeVacancy(['title' => 'Frontend Engineer', 'company' => 'A Corp']);
        $b = $this->makeVacancy(['title' => 'Backend Engineer', 'company' => 'B Corp']);
        $this->script(
            $this->toolCall('compare_jobs', ['job_ids' => [$a->id, $b->id]]),
            $this->toolCall('remember_preference', ['fact' => 'Looking for remote frontend roles', 'category' => 'role']),
            $this->text('Pilih A.'),
        );
        (new KawanKerjaService)->chat('bandingkan loker itu, dan ingat aku cari frontend remote', $user);

        $this->assertSame(['A Corp', 'B Corp'], array_column($this->toolResult(1)['jobs'], 'company'));
        $this->assertSame(['Looking for remote frontend roles'], array_column(NaraMemory::listFor($user), 'fact'));

        // The next conversation starts with the memory in the prompt; forgetting removes it.
        $memory = NaraMemory::where('user_id', $user->id)->sole();
        $this->script($this->toolCall('forget_preference', ['memory_id' => $memory->id]), $this->text('Oke, kulupakan.'));
        (new KawanKerjaService)->chat('lupakan soal frontend remote itu', $user);
        Http::assertSent(fn (Request $r) => str_contains(self::systemPrompt($r), 'Looking for remote frontend roles'));
        $this->assertSame([], NaraMemory::listFor($user));
        $this->assertFalse(NaraMemory::where('user_id', User::factory()->create()->id)->exists());
    }

    public function test_memory_is_private_capped_and_never_duplicated(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        foreach (range(1, NaraMemory::MAX + 3) as $i) {
            NaraMemory::remember($user, "Preference number {$i}");
        }
        NaraMemory::remember($user, 'Preference number '.(NaraMemory::MAX + 3)); // same again
        NaraMemory::remember($other, 'Mine');

        $this->assertCount(NaraMemory::MAX, NaraMemory::listFor($user));
        $this->assertSame(['Mine'], array_column(NaraMemory::listFor($other), 'fact'));
        $this->assertFalse(collect(NaraMemory::listFor($user))->contains('fact', 'Preference number 1'), 'the oldest are dropped');
    }

    public function test_profile_details_reach_nara(): void
    {
        $user = User::factory()->create();
        \App\Models\UserEducation::create(['user_id' => $user->id, 'institution_name' => 'Institut Teknologi Bandung', 'degree' => 'Bachelor', 'major' => 'Informatika', 'start_date' => '2015-08-01', 'end_date' => '2019-07-01']);
        \App\Models\UserProject::create(['user_id' => $user->id, 'project_name' => 'Kanvas UI', 'role' => 'Maintainer', 'description' => 'Design system', 'technologies' => 'React, TypeScript', 'start_date' => '2022-01-01']);

        $context = (new KawanKerjaService)->buildUserContext($user);

        $this->assertSame('Institut Teknologi Bandung', $context['education'][0]['school']);
        $this->assertSame('Kanvas UI', $context['projects'][0]['name']);
        $this->assertArrayHasKey('remembered_preferences', $context);
    }

    public function test_heavy_tasks_think_harder_and_light_ones_stay_quick(): void
    {
        config(['services.openai.strong_model' => 'strong-model', 'services.openai.reasoning_effort' => 'low', 'services.openai.strong_reasoning_effort' => 'medium']);
        $service = new KawanKerjaService;

        $this->assertTrue($service->wantsStrongModel('Tolong tuliskan cover letter untuk posisi data analyst'));
        $this->assertTrue($service->wantsStrongModel('Please review my answer'));
        $this->assertTrue($service->wantsStrongModel(str_repeat('Saya pernah memimpin tim kecil. ', 15)), 'a long pasted text');
        $this->assertTrue($service->wantsStrongModel(str_repeat('kata ', 20), [['role' => 'model', 'content' => "Pertanyaan pertama:\n\n> Ceritakan tantanganmu?"]]), 'an answer to an interview question');
        $this->assertFalse($service->wantsStrongModel('carikan loker frontend di jakarta'));
        $this->assertFalse($service->wantsStrongModel('apa langkah saya berikutnya?'));

        $sent = [];
        Http::fake(function (Request $request) use (&$sent) {
            $sent[] = [$request['model'], $request['reasoning_effort']];

            return Http::response($this->text('Baik.'));
        });
        $service->chat('Tolong tuliskan email follow-up untuk recruiter', User::factory()->create());
        $this->assertSame(['strong-model', 'medium'], $sent[0]);
        $service->chat('apa langkah saya berikutnya?', User::factory()->create());
        $this->assertSame(['gpt-5-mini', 'low'], $sent[1]);

        config(['services.openai.strong_model' => null]);
        $service->chat('Tolong tuliskan cover letter', User::factory()->create());
        $this->assertSame(['gpt-5-mini', 'medium'], $sent[2], 'no larger model configured: the same model thinks more');
    }

    public function test_the_api_key_never_reaches_the_log(): void
    {
        $logged = [];
        \Illuminate\Support\Facades\Log::listen(function ($event) use (&$logged) {
            $logged[] = $event->message;
        });
        Http::fake(fn () => throw new \Illuminate\Http\Client\ConnectionException('cURL error 28: timed out for https://x.test/?key=fake-test-key and fake-test-key'));

        $result = (new KawanKerjaService)->chat('apa langkah saya berikutnya?', User::factory()->create());

        $this->assertFalse($result['success']);
        $this->assertNotEmpty($logged);
        $this->assertStringNotContainsString('fake-test-key', implode(' ', $logged));
    }

    public function test_the_stream_sends_progress_text_then_the_final_reply_with_cards(): void
    {
        $user = User::factory()->create();
        $job = $this->makeVacancy(['title' => 'Data Analyst', 'region' => 'Kota Bandung', 'company' => 'Maju Jaya']);
        $this->openAi($this->openAiStream(['tool' => 'search_jobs', 'args' => ['keywords' => ['data analyst']]]), $this->openAiStream('Ini pilihannya:', "\n\n[job:{$job->id}]"));

        $body = $this->actingAs($user)->post(route('kawankerja.stream'), ['message' => 'cari data analyst'])->assertOk()->streamedContent();

        $this->assertStringContainsString('"status":"Searching the TraKerja job catalog\\u2026"', $body);
        $this->assertStringContainsString('"chunk":"Ini pilihannya:"', $body);
        $this->assertMatchesRegularExpression('/"final":"Ini pilihannya:[^"]*\[job:'.$job->id.'\|Data Analyst\|Maju Jaya\|/', $body);
        $this->assertStringContainsString('"id":', $body);
    }

    public function test_explicit_memory_requests_force_the_tool_so_nara_cannot_just_claim_it(): void
    {
        $this->assertSame('remember_preference', KawanKerjaService::forcedTool('Ingat ya, aku cari frontend remote'));
        $this->assertSame('remember_preference', KawanKerjaService::forcedTool('Remember that I prefer remote'));
        $this->assertSame('forget_preference', KawanKerjaService::forcedTool('lupakan soal gaji tadi'));
        $this->assertNull(KawanKerjaService::forcedTool('ingatkan aku besok interview'), 'a reminder is not a preference');
        $this->assertNull(KawanKerjaService::forcedTool('aku ingat dulu pernah kerja di gojek'));
        $this->assertTrue(KawanKerjaService::asksWhatIsRemembered('Apa yang kamu ingat tentang aku?'));
        $this->assertFalse(KawanKerjaService::asksWhatIsRemembered('carikan loker frontend'));

        $user = User::factory()->create();
        $this->script($this->toolCall('remember_preference', ['fact' => 'Looking for remote frontend roles']), $this->text('Sudah kucatat.'));
        (new KawanKerjaService)->chat('Ingat ya, aku cari frontend remote', $user);
        $first = Http::recorded()[0][0]->data();
        $this->assertSame(['type' => 'function', 'function' => ['name' => 'remember_preference']], $first['tool_choice']);
        $this->assertSame('auto', Http::recorded()[1][0]->data()['tool_choice'], 'only the first round is forced');

        $this->script($this->text('Kamu mencari frontend remote.'));
        (new KawanKerjaService)->chat('Apa yang kamu ingat tentang aku?', $user);
        $this->assertSame('none', Http::recorded()[0][0]->data()['tool_choice']);
    }

    public function test_job_cards_carry_the_company_logo_when_it_is_safe(): void
    {
        $user = User::factory()->create();
        $finder = app(NaraJobFinder::class);
        $job = $this->makeVacancy(['title' => 'Data Analyst', 'company' => 'Maju Jaya']);
        $job->company->update(['logo_url' => 'https://cdn.example.com/logo|x.png']);
        $plain = $this->makeVacancy(['title' => 'Data Engineer', 'company' => 'Tanpa Logo']);
        $plain->company->update(['logo_url' => 'http://insecure.example.com/logo.png']);

        $reply = $finder->expand("[job:{$job->id}] [job:{$plain->id}]", [$job->id, $plain->id], $user->id);

        $this->assertStringContainsString('|0|https://cdn.example.com/logo%7Cx.png]', $reply, 'a | in the URL cannot break the tag');
        $this->assertMatchesRegularExpression('/\\[job:'.$plain->id.'\\|Data Engineer\\|Tanpa Logo\\|[^\\]]*\\|0\\|\\]/', $reply, 'no http logo');
    }
}
