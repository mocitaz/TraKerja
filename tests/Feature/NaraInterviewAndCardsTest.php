<?php

namespace Tests\Feature;

use App\Models\NaraInterview;
use App\Models\NaraMessage;
use App\Models\User;
use App\Services\NaraClientStopped;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\FakesOpenAi;
use Tests\Concerns\MakesVacancies;
use Tests\TestCase;

/** Structured interview practice, suggestion chips, the comparison table and conversation controls. */
class NaraInterviewAndCardsTest extends TestCase
{
    use FakesOpenAi, MakesVacancies, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->seed(JobEngineReferenceSeeder::class);
        config(['services.openai.key' => 'fake-test-key', 'services.openai.strong_model' => null]);
    }

    private function toolCall(string $name, array $args): array
    {
        return $this->openAiToolCall($name, $args, 'call_'.$name);
    }

    private function text(string $text): array
    {
        return $this->openAiText($text);
    }

    private function script(array ...$replies): void
    {
        $this->openAi(...$replies);
    }

    private function ask(User $user, string $message): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($user)->postJson(route('kawankerja.chat'), ['message' => $message])->assertOk();
    }

    public function test_the_plan_goes_behavioural_then_technical_then_motivation(): void
    {
        $this->assertSame(['behavioral', 'behavioral', 'technical', 'technical', 'motivation'], NaraInterview::planFor(5));
        $this->assertCount(3, NaraInterview::planFor(1), 'at least 3 questions');
        $this->assertCount(8, NaraInterview::planFor(50), 'at most 8 questions');
    }

    public function test_an_interview_session_is_stored_scored_and_summarised(): void
    {
        $user = User::factory()->create();
        $this->script($this->toolCall('start_interview', ['role' => 'Data Analyst', 'company' => 'Acme']), $this->text('> Ceritakan proyek data terakhirmu.'));
        $reply = $this->ask($user, 'Latih aku interview data analyst')->json('reply');

        $session = NaraInterview::activeFor($user);
        $this->assertSame(5, $session->total);
        $this->assertStringContainsString('[interview:0|5|', $reply);

        $this->script($this->toolCall('record_answer', ['question_number' => 1, 'score' => 7.5, 'strength' => 'Jelas', 'improvement' => 'Tambah angka']), $this->text('> Pertanyaan 2'));
        $this->ask($user, 'Jawaban saya...');
        $this->assertSame(7.5, $session->fresh()->average());
        $this->assertSame(1, $session->fresh()->answered());

        // The next prompt carries the running session so the model can continue it.
        $this->script($this->text('ok'));
        $this->ask($user, 'Lanjut');
        Http::assertSent(fn ($r) => str_contains(self::systemPrompt($r), 'active_interview'));

        $this->script($this->toolCall('record_answer', ['question_number' => 2, 'score' => 99]), $this->toolCall('finish_interview', ['reason' => 'stopped']), $this->text('Ringkasan'));
        $this->ask($user, 'Udahan');
        $this->assertNull(NaraInterview::activeFor($user));
        $this->assertSame(10.0, (float) $session->fresh()->answers[1]['score'], 'scores are capped at 10');
    }

    public function test_bad_interview_calls_are_refused_and_sessions_are_private(): void
    {
        $user = User::factory()->create();
        $this->script($this->toolCall('record_answer', ['question_number' => 1, 'score' => 5]), $this->text('Belum ada sesi.'));
        $this->ask($user, 'nilai jawaban saya');
        $this->assertSame(0, NaraInterview::count());

        $this->script($this->toolCall('start_interview', ['role' => 'PM']), $this->toolCall('record_answer', ['question_number' => 9, 'score' => 5]), $this->text('x'));
        $this->ask($user, 'latihan interview');
        $this->assertSame(0, NaraInterview::activeFor($user)->answered(), 'question 9 of 5 is rejected');

        $this->assertNull(NaraInterview::activeFor(User::factory()->create()));
    }

    public function test_suggestion_chips_keep_the_last_tag_and_at_most_three(): void
    {
        $user = User::factory()->create();
        $this->script($this->text("Halo\n[suggest:Satu|Dua]\nIsi\n[suggest:Tulis cover letter|Latih interview|Bandingkan loker|Cari yang remote|Simpan pencarian]"));
        $reply = $this->ask($user, 'Apa langkah berikutnya untuk lamaran saya?')->json('reply');

        $this->assertSame(1, substr_count($reply, '[suggest:'));
        $this->assertStringContainsString('[suggest:Tulis cover letter|Latih interview|Bandingkan loker]', $reply);
        $this->assertStringNotContainsString('Cari yang remote', $reply);
    }

    public function test_a_comparison_only_uses_jobs_offered_in_this_conversation(): void
    {
        $user = User::factory()->create();
        $a = $this->makeVacancy(['title' => 'Data Analyst']);
        $b = $this->makeVacancy(['title' => 'Data Scientist']);
        $this->script($this->toolCall('compare_jobs', ['job_ids' => [$a->id, $b->id]]), $this->text('Bandingannya begini.'));
        $reply = $this->ask($user, 'bandingkan loker ini')->json('reply');

        $this->assertStringContainsString('[compare:', $reply, 'added even if the model forgot the tag');
        $payload = json_decode(base64_decode(strtr(explode(']', explode('[compare:', $reply)[1])[0], '-_', '+/')), true);
        $this->assertEqualsCanonicalizing([$a->id, $b->id], array_column($payload['jobs'], 'id'));
    }

    public function test_the_last_message_can_be_taken_back_for_editing(): void
    {
        $user = User::factory()->create();
        NaraMessage::record($user, 'Pertama', 'Jawab 1', 'career');
        NaraMessage::record($user, 'Kedua', 'Jawab 2', 'career');
        $other = User::factory()->create();
        NaraMessage::record($other, 'Orang lain', 'Jawab', 'career');

        $this->actingAs($user)->postJson(route('kawankerja.undo-last'))->assertOk()->assertJson(['text' => 'Kedua']);
        $this->assertSame(2, NaraMessage::where('user_id', $user->id)->count());
        $this->assertSame(2, NaraMessage::where('user_id', $other->id)->count(), "other users' history is untouched");

        $this->actingAs(User::factory()->create())->postJson(route('kawankerja.undo-last'))->assertOk()->assertJson(['text' => null]);
    }

    public function test_the_prompt_is_consistent_and_documents_the_tags(): void
    {
        $user = User::factory()->create();
        $service = new \App\Services\KawanKerjaService;
        $build = new \ReflectionMethod($service, 'buildSystemPrompt');
        $prompt = $build->invoke($service, $service->buildUserContext($user), 'auto', null, 'id', true);

        $this->assertSame(1, substr_count($prompt, '60-120'), 'one length rule, not two');
        foreach (['[job:', '[suggest:', '[draft:'] as $tag) {
            $this->assertStringContainsString($tag, $prompt);
        }
        $this->assertTrue(class_exists(NaraClientStopped::class));
    }
}
