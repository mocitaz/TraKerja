<?php

namespace Tests\Feature;

use App\Jobs\NotifyVacancyClosed;
use App\JobEngine\Personalization\Embeddings;
use App\JobEngine\Personalization\MatchScorer;
use App\Livewire\ExploreJobs;
use App\Livewire\Layout\InboxBell;
use App\Models\FeedClient;
use App\Models\JobApplication;
use App\Models\JobBookmark;
use App\Models\SavedJobSearch;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserSkill;
use App\Notifications\SavedSearchAlert;
use App\Notifications\VacancyClosed;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\Concerns\MakesVacancies;
use Tests\TestCase;

/** TraKerja 2.0, Fase 6: Explore as home, landing, CV match, alerts, closed-job notices, feed API. */
class Fase6PersonalizationTest extends TestCase
{
    use MakesVacancies, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->seed(JobEngineReferenceSeeder::class);
        config(['services.openai.jobs.key' => 'test-key']);
    }

    // ---- Explore is home, landing and public search ----

    public function test_signed_in_users_land_on_explore_unless_it_is_in_maintenance(): void
    {
        $user = User::factory()->create();
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('jobs.explore', absolute: false));
        $this->post('/logout');

        Setting::set('explore_maintenance', true);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_landing_shows_live_jobs_and_guests_can_search_public_jobs(): void
    {
        $this->makeVacancy(['title' => 'Staff Admin Gudang', 'company' => 'Sumber Rejeki', 'region' => 'Jakarta Selatan']);
        $this->makeVacancy(['title' => 'Barista', 'company' => 'Kopi Kita', 'region' => 'Kota Surabaya']);
        $this->makeVacancy(['title' => 'Kasir Lama', 'status' => 'closed']);

        $this->get('/')->assertOk()->assertSee('lp-jobs-stats', false)->assertSee('Staff Admin Gudang')->assertDontSee('Kasir Lama');

        $this->get('/loker')->assertOk()->assertSee('Staff Admin Gudang')->assertSee('Barista')->assertDontSee('Kasir Lama')
            ->assertDontSee('noindex', false);
        $this->get('/loker?q=admin&lokasi=Jakarta')->assertOk()->assertSee('Staff Admin Gudang')->assertDontSee('Barista')
            ->assertSee('<meta name="robots" content="noindex">', false);
    }

    // ---- CV match ----

    private function fakeEmbeddings(): void
    {
        // Vectors chosen by keyword so similarity is predictable: engineering texts point one way, the rest another.
        Http::fake(['api.openai.com/v1/embeddings' => function ($request) {
            $data = [];
            foreach ($request['input'] as $i => $text) {
                $tech = (bool) preg_match('/engineer|laravel|react|developer/i', $text);
                $data[] = ['index' => $i, 'embedding' => array_pad($tech ? [1.0, 0.1] : [0.1, 1.0], 256, 0.0)];
            }

            return Http::response(['data' => $data, 'usage' => ['prompt_tokens' => 40 * count($data)]]);
        }]);
    }

    private function engineer(): User
    {
        $user = User::factory()->create();
        foreach (['Laravel', 'React'] as $skill) {
            UserSkill::create(['user_id' => $user->id, 'skill_name' => $skill]);
        }
        $user->profile()->create(['headline' => 'Software engineer, Laravel and React']);

        return $user;
    }

    public function test_match_scores_rank_relevant_jobs_and_explain_skills(): void
    {
        $this->fakeEmbeddings();
        $fit = $this->makeVacancy(['title' => 'Laravel Developer', 'skills' => ['Laravel', 'MySQL']]);
        $other = $this->makeVacancy(['title' => 'Kasir Toko', 'skills' => ['Kasir']]);
        $this->assertSame(2, app(Embeddings::class)->embedVacancies());
        $this->assertSame(0, app(Embeddings::class)->embedVacancies(), 'unchanged vacancies are not embedded again');

        $user = $this->engineer();
        $scores = app(MatchScorer::class)->forVacancies($user, collect([$fit, $other]));
        $this->assertGreaterThan(80, $scores[$fit->id]['score']);
        $this->assertSame(['Laravel'], $scores[$fit->id]['matched']);
        $this->assertSame(['MySQL'], $scores[$fit->id]['missing']);
        $this->assertSame(0, $scores[$other->id]['score']);
        $this->assertSame([$fit->id, $other->id], app(MatchScorer::class)->rankedIds($user));

        Livewire::actingAs($user)->test(ExploreJobs::class)->set('sortBy', 'match')
            ->assertViewHas('postings', fn ($rows) => $rows->first()->id === $fit->id)
            ->assertSee('% match');
    }

    public function test_match_scores_are_premium_when_monetization_is_on(): void
    {
        $this->fakeEmbeddings();
        $this->makeVacancy(['title' => 'Laravel Developer']);
        app(Embeddings::class)->embedVacancies();
        Setting::set('monetization_enabled', true);

        Livewire::actingAs($this->engineer())->test(ExploreJobs::class)
            ->assertSee('Match scores are a Premium feature')->assertDontSee('% match');
    }

    public function test_seniority_gap_lowers_the_score(): void
    {
        $base = MatchScorer::score(0.7, [], [], 'lead', 10.0)['score'];
        $gap = MatchScorer::score(0.7, [], [], 'lead', 1.0);
        $this->assertSame(100, $base);
        $this->assertSame(80, $gap['score']);
        $this->assertTrue($gap['level_gap']);
    }

    // ---- Saved-search alerts ----

    public function test_alerts_send_one_digest_for_new_matching_jobs_only(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $search = SavedJobSearch::create(['user_id' => $user->id, 'name' => 'Admin', 'filters' => ['search' => 'admin'], 'fingerprint' => 'a',
            'alert_channel' => 'email', 'last_alerted_at' => now()->subDay()]);
        SavedJobSearch::create(['user_id' => $user->id, 'name' => 'Off', 'filters' => ['search' => 'kasir'], 'fingerprint' => 'b', 'alert_channel' => 'none']);
        $this->makeVacancy(['title' => 'Staff Admin Gudang']);
        $this->makeVacancy(['title' => 'Admin Lama', 'first_seen_at' => now()->subDays(3)]);
        $this->makeVacancy(['title' => 'Kasir Toko']);

        $this->artisan('jobs:alerts')->assertSuccessful();

        Notification::assertSentToTimes($user, SavedSearchAlert::class, 1);
        Notification::assertSentTo($user, SavedSearchAlert::class, fn ($n) => $n->searches[0]['total'] === 1 && $n->searches[0]['jobs'][0]['title'] === 'Staff Admin Gudang');
        $this->assertTrue($search->fresh()->last_alerted_at->isToday());

        // Not due again the same day.
        $this->artisan('jobs:alerts')->assertSuccessful();
        Notification::assertSentToTimes($user, SavedSearchAlert::class, 1);
    }

    public function test_alert_toggle_requires_a_narrowing_search_and_starts_from_now(): void
    {
        $user = User::factory()->create();
        $empty = SavedJobSearch::create(['user_id' => $user->id, 'name' => 'Everything', 'filters' => [], 'fingerprint' => 'e']);
        $search = SavedJobSearch::create(['user_id' => $user->id, 'name' => 'Remote', 'filters' => ['selectedWorkType' => 'Remote'], 'fingerprint' => 'r']);

        $component = Livewire::actingAs($user)->test(ExploreJobs::class)
            ->call('toggleSearchAlert', $empty->id)->assertSet('feedback', __('Add a keyword or filter to this search before turning on alerts.'))
            ->call('toggleSearchAlert', $search->id);
        $this->assertTrue($search->fresh()->alertsOn());
        $this->assertTrue($search->fresh()->last_alerted_at->gt(now()->subMinute()));
        $component->call('toggleSearchAlert', $search->id);
        $this->assertFalse($search->fresh()->alertsOn());

        $this->assertThrows(fn () => Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)->call('toggleSearchAlert', $search->id),
            \Illuminate\Database\Eloquent\ModelNotFoundException::class);
    }

    // ---- Closed-job notices ----

    public function test_closing_a_vacancy_notifies_active_applicants_and_savers_once(): void
    {
        Notification::fake();
        $vacancy = $this->makeVacancy(['title' => 'Staff Admin Gudang', 'company' => 'Sumber Rejeki']);
        [$applicant, $rejected, $saver, $optedOut] = User::factory()->count(4)->create()->all();
        $optedOut->update(['notify_job_closed' => false]);
        $application = JobApplication::factory()->create(['user_id' => $applicant->id, 'vacancy_id' => $vacancy->id, 'application_status' => 'On Process']);
        JobApplication::factory()->create(['user_id' => $rejected->id, 'vacancy_id' => $vacancy->id, 'application_status' => 'Rejected']);
        JobBookmark::create(['user_id' => $saver->id, 'vacancy_id' => $vacancy->id]);
        JobBookmark::create(['user_id' => $optedOut->id, 'vacancy_id' => $vacancy->id]);

        Queue::fake();
        $vacancy->update(['status' => 'closed']);
        Queue::assertPushed(NotifyVacancyClosed::class, fn ($job) => $job->vacancyId === $vacancy->id);

        (new NotifyVacancyClosed($vacancy->id))->handle();
        Notification::assertSentTo($applicant, VacancyClosed::class, fn ($n) => $n->relation === 'applied' && $n->applicationId === $application->id);
        Notification::assertSentTo($saver, VacancyClosed::class, fn ($n) => $n->relation === 'saved');
        Notification::assertNotSentTo($rejected, VacancyClosed::class);
        Notification::assertNotSentTo($optedOut, VacancyClosed::class);
    }

    public function test_closed_notice_is_not_repeated_and_shows_in_the_inbox(): void
    {
        $vacancy = $this->makeVacancy(['title' => 'Staff Admin Gudang']);
        $user = User::factory()->create();
        JobBookmark::create(['user_id' => $user->id, 'vacancy_id' => $vacancy->id]);
        $vacancy->update(['status' => 'closed']); // sync queue in tests: handled now

        (new NotifyVacancyClosed($vacancy->id))->handle();
        $this->assertSame(1, $user->notifications()->count());

        Livewire::actingAs($user)->test(InboxBell::class)->assertSee('1')->call('toggle')
            ->assertSee('Job closed: Staff Admin Gudang')->call('markAllRead');
        $this->assertSame(0, $user->unreadNotifications()->count());
    }

    // ---- Feed API ----

    public function test_feed_requires_a_key_and_pages_with_a_cursor(): void
    {
        [, $key] = FeedClient::issue('asep');
        foreach (range(1, 3) as $i) {
            $this->makeVacancy(['title' => "Staff Admin {$i}"]);
        }

        $this->getJson('/api/v1/vacancies')->assertUnauthorized();
        $this->getJson('/api/v1/vacancies', ['Authorization' => 'Bearer wrong'])->assertUnauthorized();

        $first = $this->getJson('/api/v1/vacancies?limit=2', ['Authorization' => "Bearer {$key}"])->assertOk()
            ->assertJsonPath('has_more', true)->assertJsonCount(2, 'data')->assertJsonStructure(['data' => [['id', 'status', 'title', 'company', 'location', 'apply_url', 'sources', 'url']]]);
        $second = $this->getJson('/api/v1/vacancies?limit=2&cursor='.urlencode($first->json('next_cursor')), ['X-Api-Key' => $key])->assertOk()
            ->assertJsonPath('has_more', false)->assertJsonCount(1, 'data');
        $ids = array_merge(array_column($first->json('data'), 'id'), array_column($second->json('data'), 'id'));
        $this->assertCount(3, array_unique($ids));
        $this->assertSame(2, FeedClient::first()->requests, "only authenticated calls are counted");
    }

    public function test_feed_sync_reports_closures_merges_and_removals(): void
    {
        [, $key] = FeedClient::issue('asep');
        $closed = $this->makeVacancy(['title' => 'Kasir']);
        $target = $this->makeVacancy(['title' => 'Staff Admin Gudang']);
        $merged = $this->makeVacancy(['title' => 'Staff Admin Gudang (copy)']);
        $scam = $this->makeVacancy(['title' => 'Kerja Rumahan']);
        $since = now()->subMinute()->toIso8601String();

        $closed->update(['status' => 'closed']);
        $merged->update(['merged_into_id' => $target->id]);
        $scam->update(['scam_flag' => 'suspected']);

        $plain = $this->getJson('/api/v1/vacancies', ['Authorization' => "Bearer {$key}"])->json('data');
        $this->assertSame([$target->public_id], array_column($plain, 'id'), 'a plain listing only shows open jobs');

        $sync = collect($this->getJson('/api/v1/vacancies?updated_since='.urlencode($since), ['Authorization' => "Bearer {$key}"])->json('data'))->keyBy('id');
        $this->assertSame('closed', $sync[$closed->public_id]['status']);
        $this->assertSame($target->public_id, $sync[$merged->public_id]['merged_into']);
        $this->assertArrayNotHasKey('title', $sync[$merged->public_id]);
        $this->assertSame('removed', $sync[$scam->public_id]['status']);
        $this->assertArrayNotHasKey('title', $sync[$scam->public_id]);
    }

    public function test_feed_rate_limit_and_revoked_keys(): void
    {
        [$client, $key] = FeedClient::issue('asep', 2);
        $headers = ['Authorization' => "Bearer {$key}"];
        $this->getJson('/api/v1/sources', $headers)->assertOk()->assertHeader('X-RateLimit-Limit', '2');
        $this->getJson('/api/v1/sources', $headers)->assertOk();
        $this->getJson('/api/v1/sources', $headers)->assertStatus(429)->assertHeader('Retry-After');

        $client->update(['is_active' => false]);
        $this->getJson('/api/v1/sources', $headers)->assertUnauthorized();
        $this->assertSame(64, strlen(FeedClient::first()->key_hash));
        $this->assertStringNotContainsString($key, json_encode(FeedClient::first()->toArray()));
    }

    // ---- Workspace activity notices ----

    public function test_application_lifecycle_creates_activity_notices_for_the_owner_only(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $app = JobApplication::factory()->create(['user_id' => $user->id, 'position' => 'Data Analyst', 'company_name' => 'Maju Jaya', 'recruitment_stage' => 'Applied', 'application_status' => 'On Process']);
        $app->update(['recruitment_stage' => 'HR - Interview']);
        $app->update(['application_status' => 'Accepted']);
        $app->delete();

        $events = $user->notifications()->oldest()->pluck('data')->pluck('event')->all();
        $this->assertSame(['application_created', 'stage_changed', 'application_accepted', 'application_deleted'], $events);

        // Someone else's application (e.g. an admin or import) never notifies this user.
        $other = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $other->id]);
        $this->assertSame(0, $other->notifications()->count());

        Livewire::actingAs($user)->test(InboxBell::class)->call('toggle')
            ->assertSee('Congratulations! You were accepted')->assertSee('Application deleted: Data Analyst')->assertSee('Today');
    }
}
