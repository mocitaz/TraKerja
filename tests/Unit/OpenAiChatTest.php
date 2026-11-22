<?php

namespace Tests\Unit;

use App\Services\Ai\OpenAiChat;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/** The shared OpenAI client: streamed tool calls arrive in pieces, schemas are converted, keys never leak. */
class OpenAiChatTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['services.openai.key' => 'sk-test-secret']);
    }

    private static function event(array $delta, ?string $finish = null): string
    {
        return 'data: '.json_encode(['choices' => [['index' => 0, 'delta' => $delta, 'finish_reason' => $finish]]])."\n\n";
    }

    public function test_a_streamed_tool_call_is_joined_from_its_pieces_and_text_is_streamed(): void
    {
        $body = self::event(['content' => 'Sebentar, '])
            .self::event(['tool_calls' => [['index' => 0, 'id' => 'call_a', 'type' => 'function', 'function' => ['name' => 'search_jobs', 'arguments' => '{"keywords":["data']]]])
            .self::event(['tool_calls' => [['index' => 0, 'function' => ['arguments' => ' analyst"],"location":"Bandung"}']]]])
            .self::event(['tool_calls' => [['index' => 1, 'id' => 'call_b', 'type' => 'function', 'function' => ['name' => 'get_job_details', 'arguments' => '{"job_ids":[7]}']]]])
            .self::event([], 'tool_calls')."data: [DONE]\n\n";
        Http::fake(['api.openai.com/*' => Http::response($body, 200, ['Content-Type' => 'text/event-stream'])]);

        $chunks = [];
        $out = (new OpenAiChat)->call('gpt-5-mini', ['messages' => [['role' => 'user', 'content' => 'hi']]], function ($c) use (&$chunks) {
            $chunks[] = $c;
        });

        $this->assertTrue($out['ok']);
        $this->assertSame(['Sebentar, '], $chunks);
        $this->assertSame('tool_calls', $out['finish']);
        $this->assertSame([['call_a', 'search_jobs', ['keywords' => ['data analyst'], 'location' => 'Bandung']], ['call_b', 'get_job_details', ['job_ids' => [7]]]],
            array_map(fn ($c) => [$c['id'], $c['name'], $c['args']], $out['calls']));
        $echo = OpenAiChat::assistantToolMessage($out);
        $this->assertSame('assistant', $echo['role']);
        $this->assertSame('{"job_ids":[7]}', $echo['tool_calls'][1]['function']['arguments']);
        Http::assertSent(fn ($r) => $r['stream'] === true && $r['model'] === 'gpt-5-mini' && $r->hasHeader('Authorization', 'Bearer sk-test-secret'));
    }

    public function test_gemini_style_declarations_become_openai_tools(): void
    {
        $tools = OpenAiChat::tools([['functionDeclarations' => [
            ['name' => 'search', 'description' => 'Find', 'parameters' => ['type' => 'OBJECT', 'properties' => ['q' => ['type' => 'STRING'], 'tags' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']]], 'required' => ['q']]],
            ['name' => 'digest', 'description' => 'Today'],
        ]]]);

        $this->assertSame('function', $tools[0]['type']);
        $this->assertSame(['type' => 'object', 'properties' => ['q' => ['type' => 'string'], 'tags' => ['type' => 'array', 'items' => ['type' => 'string']]], 'required' => ['q']], $tools[0]['function']['parameters']);
        $this->assertSame('{"type":"object","properties":{}}', json_encode($tools[1]['function']['parameters']));
    }

    public function test_failures_return_quietly_and_the_key_never_reaches_the_log(): void
    {
        $logged = [];
        \Illuminate\Support\Facades\Log::listen(function ($e) use (&$logged) {
            $logged[] = $e->message;
        });
        Http::fake(['api.openai.com/*' => Http::response(['error' => ['message' => 'Incorrect API key provided: sk-test-secret']], 401)]);

        $out = (new OpenAiChat)->callModels(['gpt-5-mini', 'gpt-5-nano'], ['messages' => [['role' => 'user', 'content' => 'hi']]]);

        $this->assertFalse($out['ok']);
        $this->assertSame(401, $out['status']);
        Http::assertSentCount(2);
        $this->assertNotEmpty($logged);
        $this->assertStringNotContainsString('sk-test-secret', implode(' ', $logged));
    }
}
