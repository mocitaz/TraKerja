<?php

namespace Tests\Feature;

use App\Models\AiAnalyzerResult;
use App\Models\CoverLetter;
use App\Models\OutreachDraft;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/** The AI Studio chat workspace: revisions as versions, one tool leading to the next, readable history. */
class AiStudioWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.openai.key' => 'test-key', 'services.openai.model' => 'gpt-5-mini', 'services.openai.studio_models' => []]);
    }

    private function letter(User $user): CoverLetter
    {
        return CoverLetter::create(['user_id' => $user->id, 'company_name' => 'Traveloka', 'job_title' => 'Frontend Engineer', 'job_description' => 'Build React apps with TypeScript for millions of travellers.', 'language' => 'en', 'tone' => 'professional', 'content' => str_repeat('I build fast, accessible React interfaces. ', 8)]);
    }

    private static function reply(string $content): array
    {
        return ['choices' => [['message' => ['role' => 'assistant', 'content' => json_encode(['content' => $content])], 'finish_reason' => 'stop']]];
    }

    public function test_a_revision_is_a_new_version_and_the_first_draft_stays(): void
    {
        $user = User::factory()->create();
        $first = $this->letter($user);
        Http::fake(['api.openai.com/*' => Http::response(self::reply(str_repeat('A shorter letter about React work. ', 3)))]);

        $url = $this->actingAs($user)->postJson(route('ai-studio.revise'), ['task' => 'cover-letter', 'id' => $first->id, 'instruction' => 'Make it shorter'])
            ->assertOk()->assertJson(['success' => true])->json('url');

        $second = CoverLetter::latest('id')->first();
        $this->assertSame($first->id, $second->root_id);
        $this->assertSame($first->id, $second->parent_id);
        $this->assertSame('Make it shorter', $second->revision_note);
        $this->assertSame('Traveloka', $second->company_name);
        $this->assertStringContainsString('shorter letter', $second->content);
        $this->assertStringContainsString('I build fast', $first->fresh()->content, 'the first draft is kept');
        Http::assertSent(fn ($r) => str_contains($r['messages'][1]['content'], 'Make it shorter') && $r['response_format']['json_schema']['strict'] === true);

        $this->get($url)->assertOk()->assertSee('Version 2 of 2')->assertSee('Make it shorter')->assertSee('2 free revisions left');
    }

    public function test_revisions_are_limited_private_and_a_failure_changes_nothing(): void
    {
        $user = User::factory()->create();
        $first = $this->letter($user);
        $this->actingAs(User::factory()->create())->postJson(route('ai-studio.revise'), ['task' => 'cover-letter', 'id' => $first->id, 'instruction' => 'Shorter'])->assertNotFound();

        Http::fake(['api.openai.com/*' => Http::response(['error' => ['message' => 'down']], 503)]);
        $this->actingAs($user)->postJson(route('ai-studio.revise'), ['task' => 'cover-letter', 'id' => $first->id, 'instruction' => 'Shorter'])->assertStatus(503);
        $this->assertSame(1, CoverLetter::count());

        foreach (range(1, 3) as $i) {
            CoverLetter::create(collect($first->getAttributes())->except(['id', 'created_at', 'updated_at'])->all() + ['root_id' => $first->id, 'parent_id' => $first->id, 'revision_note' => "Revision {$i}"]);
        }
        $this->postJson(route('ai-studio.revise'), ['task' => 'cover-letter', 'id' => $first->id, 'instruction' => 'Shorter'])->assertUnprocessable();
        $this->postJson(route('ai-studio.revise'), ['task' => 'photo', 'id' => $first->id, 'instruction' => 'Shorter'])->assertUnprocessable();
    }

    public function test_the_next_tool_starts_with_the_role_and_job_of_the_previous_result(): void
    {
        $user = User::factory()->create();
        $review = AiAnalyzerResult::create(['user_id' => $user->id, 'job_description' => 'Lead the frontend team at Tokopedia.', 'resume_file_name' => 'cv.pdf',
            'analysis_result' => ['schema_version' => 3, 'target_role' => 'Lead Frontend Engineer · Tokopedia']]);
        $this->actingAs($user)->get(route('ai-studio.index', ['task' => 'cover-letter', 'from' => 'analyzer:'.$review->id]))->assertOk()
            ->assertViewHas('prefill', ['job_title' => 'Lead Frontend Engineer', 'company_name' => 'Tokopedia', 'job_description' => 'Lead the frontend team at Tokopedia.']);

        $letter = $this->letter($user);
        $this->get(route('ai-studio.index', ['task' => 'outreach', 'from' => 'cover-letter:'.$letter->id]))
            ->assertViewHas('prefill', fn ($prefill) => $prefill['company_name'] === 'Traveloka' && $prefill['job_title'] === 'Frontend Engineer');

        // Someone else's result is never read.
        $this->actingAs(User::factory()->create())->get(route('ai-studio.index', ['task' => 'cover-letter', 'from' => 'analyzer:'.$review->id]))->assertViewHas('prefill', []);
    }

    public function test_the_workspace_greets_groups_history_and_offers_the_next_steps(): void
    {
        $user = User::factory()->create(['name' => 'Nadia Putri']);
        $letter = $this->letter($user);
        OutreachDraft::create(['user_id' => $user->id, 'company_name' => 'Gojek', 'job_title' => 'UI Engineer', 'channel' => 'Email', 'tone' => 'Professional', 'content' => 'Hello team, I would like to introduce myself for the UI role.']);

        $this->actingAs($user)->get(route('ai-studio.index'))->assertOk()
            ->assertSee('What can I help you with today, Nadia?')->assertSee('Today')->assertSee('Gojek · UI Engineer')->assertSee('Search history');
        $this->get(route('ai-studio.index', ['task' => 'cover-letter', 'id' => $letter->id]))->assertOk()
            ->assertSee('Message the recruiter')->assertSee('Check my CV for this role')->assertSee('Make it shorter')->assertSee('workspace-page__container', false);
    }
}
