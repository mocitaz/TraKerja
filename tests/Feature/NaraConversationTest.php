<?php

namespace Tests\Feature;

use App\JobEngine\Personalization\NaraNudges;
use App\Models\JobApplication;
use App\Models\NaraMessage;
use App\Models\SavedJobSearch;
use App\Models\User;
use App\Services\KawanKerjaService;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\MakesVacancies;
use Tests\TestCase;

/** Nara remembers conversations per user, can be rated, knows the job behind an application and speaks first. */
class NaraConversationTest extends TestCase
{
    use MakesVacancies, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->seed(JobEngineReferenceSeeder::class);
        config(['services.openai.key' => 'fake-test-key']);
    }

    private function fakeReply(string $text): void
    {
        Http::fake(['https://api.openai.com/*' => Http::response(['choices' => [['message' => ['role' => 'assistant', 'content' => $text], 'finish_reason' => 'stop']]])]);
    }

    public function test_the_conversation_is_stored_per_user_and_survives_a_new_session(): void
    {
        $this->fakeReply('Fokus ke dua lamaran yang sudah interview.');
        $user = User::factory()->create();

        $reply = $this->actingAs($user)->postJson(route('kawankerja.chat'), ['message' => 'Apa prioritas saya minggu ini?'])->assertOk();
        $this->assertNotNull($reply->json('message_id'));

        $this->flushSession(); // logout, another device
        $history = $this->actingAs($user)->getJson(route('kawankerja.history'))->assertOk()->json('history');
        $this->assertSame(['user', 'model'], array_column($history, 'role'));
        $this->assertSame($reply->json('message_id'), $history[1]['id']);

        // The next question carries the stored exchange to the model.
        $this->actingAs($user)->postJson(route('kawankerja.chat'), ['message' => 'Kenapa yang itu?'])->assertOk();
        Http::assertSent(fn ($request) => count($request['messages'] ?? []) === 4);

        $this->actingAs(User::factory()->create())->getJson(route('kawankerja.history'))->assertJson(['history' => []]);
        $this->actingAs($user)->postJson(route('kawankerja.clear'))->assertOk();
        $this->assertSame(0, NaraMessage::where('user_id', $user->id)->count());
    }

    public function test_a_session_conversation_from_before_is_moved_once(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->withSession(['kawankerja_history' => [['role' => 'user', 'content' => 'Hai'], ['role' => 'model', 'content' => 'Halo']]]);

        $this->getJson(route('kawankerja.history'))->assertOk()->assertJsonCount(2, 'history');
        $this->getJson(route('kawankerja.history'))->assertJsonCount(2, 'history'); // not copied twice
        $this->assertSame(2, NaraMessage::where('user_id', $user->id)->count());
    }

    public function test_only_your_own_replies_can_be_rated(): void
    {
        $user = User::factory()->create();
        $reply = NaraMessage::record($user, 'Halo', 'Jawaban', 'career');
        $question = NaraMessage::where('user_id', $user->id)->where('role', 'user')->first();

        $this->actingAs($user)->postJson(route('kawankerja.feedback'), ['message_id' => $reply->id, 'rating' => 1])->assertOk()->assertJson(['rating' => 1]);
        $this->assertNotNull($reply->fresh()->rated_at);
        $this->actingAs($user)->postJson(route('kawankerja.feedback'), ['message_id' => $reply->id, 'rating' => 0])->assertOk();
        $this->assertNull($reply->fresh()->rating, 'pressing again takes it back');

        $this->actingAs($user)->postJson(route('kawankerja.feedback'), ['message_id' => $question->id, 'rating' => 1])->assertNotFound();
        $this->actingAs(User::factory()->create())->postJson(route('kawankerja.feedback'), ['message_id' => $reply->id, 'rating' => -1])->assertNotFound();
        $this->actingAs($user)->postJson(route('kawankerja.feedback'), ['message_id' => $reply->id, 'rating' => 5])->assertUnprocessable();
    }

    public function test_old_messages_are_pruned_beyond_the_limit(): void
    {
        $user = User::factory()->create();
        foreach (range(1, NaraMessage::KEEP / 2 + 3) as $i) {
            NaraMessage::record($user, "Q{$i}", "A{$i}", 'career');
        }
        $this->assertSame(NaraMessage::KEEP, NaraMessage::where('user_id', $user->id)->count());
        $context = NaraMessage::contextFor($user);
        $this->assertCount(NaraMessage::CONTEXT, $context);
        $this->assertSame('A'.(NaraMessage::KEEP / 2 + 3), end($context)['content'], 'newest last');
    }

    public function test_interview_practice_knows_the_job_behind_an_application(): void
    {
        $user = User::factory()->create();
        $vacancy = $this->makeVacancy(['title' => 'Data Analyst', 'skills' => ['SQL', 'Tableau'], 'seniority' => 'entry', 'summary' => 'Menganalisis data penjualan harian.']);
        JobApplication::factory()->create(['user_id' => $user->id, 'vacancy_id' => $vacancy->id, 'position' => 'Data Analyst', 'application_status' => 'On Process', 'interview_date' => now()->addDay()]);

        $context = (new KawanKerjaService)->buildUserContext($user);

        $posting = $context['upcoming_interviews'][0]['job_posting'];
        $this->assertSame(['SQL', 'Tableau'], $posting['skills']);
        $this->assertSame('entry', $posting['level']);
        $this->assertTrue($posting['still_open']);
        $this->assertSame('Menganalisis data penjualan harian.', $context['active_applications'][0]['job_posting']['summary']);
    }

    public function test_nara_brings_up_interviews_closed_jobs_and_new_matches(): void
    {
        $user = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $user->id, 'company_name' => 'Maju Jaya', 'position' => 'Analyst', 'interview_date' => now()->addHours(20), 'is_archived' => false]);
        $closed = $this->makeVacancy(['title' => 'Closed role', 'status' => 'closed']);
        JobApplication::factory()->create(['user_id' => $user->id, 'vacancy_id' => $closed->id, 'application_status' => 'On Process', 'is_archived' => false]);
        $this->makeVacancy(['title' => 'Data Analyst', 'region' => 'Kota Bandung']);
        SavedJobSearch::create(['user_id' => $user->id, 'name' => 'Data Bandung', 'filters' => ['search' => 'data analyst', 'selectedLocation' => 'Kota Bandung'], 'fingerprint' => 'x']);

        $nudges = app(NaraNudges::class)->for($user);

        $this->assertCount(3, $nudges);
        $this->assertStringContainsString('Maju Jaya', $nudges[0]['text']);
        $this->assertStringContainsString('closed', $nudges[1]['text']);
        $this->assertStringContainsString('Data Bandung', $nudges[2]['text']);
        // The new-jobs prompt is a job request, so Nara searches the catalogue for it.
        $this->assertNotNull(app(\App\JobEngine\Personalization\NaraJobFinder::class)->criteria($nudges[2]['prompt']));

        $this->actingAs($user)->getJson(route('kawankerja.history'))->assertJsonCount(3, 'nudges');
        $this->actingAs(User::factory()->create())->getJson(route('kawankerja.history'))->assertJson(['nudges' => []]);
    }

    public function test_the_daily_recap_counts_nara_ratings(): void
    {
        $user = User::factory()->create();
        NaraMessage::record($user, 'a', 'b', 'career')->update(['rating' => 1, 'rated_at' => now()]);
        NaraMessage::record($user, 'c', 'd', 'career')->update(['rating' => -1, 'rated_at' => now()]);
        config(['services.telegram.enabled' => true, 'services.telegram.bot_token' => 't', 'services.telegram.channel_id' => 'c', 'services.telegram.allow_non_production' => true]);
        $sent = null;
        $telegram = new class extends \App\Services\TelegramService {
            public ?string $text = null;

            public function sendMessage(string $text, ?array $inlineKeyboard = null, ?string $targetChatId = null): bool
            {
                $this->text = $text;

                return true;
            }
        };
        $telegram->sendDailyRecap('evening');
        $this->assertStringContainsString('<b>Nara:</b> 2 replies · 👍 1 · 👎 1 (50% helpful)', $telegram->text);
    }

    public function test_nara_answers_in_the_language_of_the_message_and_falls_back_to_the_setting(): void
    {
        $this->assertSame('id', KawanKerjaService::messageLanguage('carikan loker frontend di jakarta'));
        $this->assertSame('en', KawanKerjaService::messageLanguage('Can you help me write a follow-up to the recruiter?'));
        $this->assertSame('id', KawanKerjaService::messageLanguage('aku mau apply ke Gojek, menurutmu gimana?'));
        $this->assertNull(KawanKerjaService::messageLanguage('frontend developer'), 'too little to tell');

        $prompts = [];
        Http::fake(function ($request) use (&$prompts) {
            $prompts[] = $request['messages'][0]['content'];

            return Http::response(['choices' => [['message' => ['role' => 'assistant', 'content' => 'ok'], 'finish_reason' => 'stop']]]);
        });
        $english = User::factory()->create(['locale' => 'en']);
        $indonesian = User::factory()->create(['locale' => 'id']);

        $this->actingAs($english)->postJson(route('kawankerja.chat'), ['message' => 'Bantu aku latihan interview untuk posisi data analyst'])->assertOk();
        $this->actingAs($indonesian)->postJson(route('kawankerja.chat'), ['message' => 'What should I do next with my applications?'])->assertOk();
        $this->actingAs($indonesian)->postJson(route('kawankerja.chat'), ['message' => 'frontend developer'])->assertOk();
        $this->actingAs($english)->postJson(route('kawankerja.chat'), ['message' => 'frontend developer'])->assertOk();

        $this->assertStringContainsString('REPLY LANGUAGE: Bahasa Indonesia', $prompts[0], 'English setting, Indonesian message');
        $this->assertStringContainsString('REPLY LANGUAGE: English', $prompts[1], 'Indonesian setting, English message');
        $this->assertStringContainsString('REPLY LANGUAGE: Bahasa Indonesia', $prompts[2], 'unclear message: the setting decides');
        $this->assertStringContainsString('REPLY LANGUAGE: English', $prompts[3]);
        $this->assertStringContainsString('pakai bahasa pengaturan TraKerja pengguna (English)', $prompts[0]);
    }
}
