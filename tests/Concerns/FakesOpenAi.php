<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\Http;

/** Chat Completions responses for tests: text, tool calls, streams, and a scripted sequence of replies. */
trait FakesOpenAi
{
    protected string $openAiUrl = 'https://api.openai.com/*';

    protected function openAiText(string $text, string $finish = 'stop'): array
    {
        return ['choices' => [['index' => 0, 'message' => ['role' => 'assistant', 'content' => $text], 'finish_reason' => $finish]]];
    }

    protected function openAiToolCall(string $name, array $args = [], string $id = 'call_1'): array
    {
        return ['choices' => [['index' => 0, 'message' => ['role' => 'assistant', 'content' => null, 'tool_calls' => [
            ['id' => $id, 'type' => 'function', 'function' => ['name' => $name, 'arguments' => json_encode((object) $args)]],
        ]], 'finish_reason' => 'tool_calls']]];
    }

    /** A streamed reply: text pieces, or ['tool' => name, 'args' => [...]] for a tool call. */
    protected function openAiStream(string|array ...$pieces): string
    {
        $events = '';
        foreach ($pieces as $i => $piece) {
            $delta = is_string($piece) ? ['content' => $piece]
                : ['tool_calls' => [['index' => 0, 'id' => 'call_'.$i, 'type' => 'function', 'function' => ['name' => $piece['tool'], 'arguments' => json_encode((object) ($piece['args'] ?? []))]]]];
            $events .= 'data: '.json_encode(['choices' => [['index' => 0, 'delta' => $delta, 'finish_reason' => null]]])."\n\n";
        }

        return $events.'data: '.json_encode(['choices' => [['index' => 0, 'delta' => (object) [], 'finish_reason' => 'stop']]])."\n\ndata: [DONE]\n\n";
    }

    /** The model answers with these replies, one per request; a new script replaces the previous one. */
    protected function openAi(array|string ...$replies): void
    {
        Http::swap(new \Illuminate\Http\Client\Factory);
        $sequence = Http::sequence();
        foreach ($replies as $reply) {
            is_string($reply) ? $sequence->push($reply, 200, ['Content-Type' => 'text/event-stream']) : $sequence->push($reply);
        }
        Http::fake([$this->openAiUrl => $sequence]);
    }

    /** The system prompt of a recorded request. */
    protected static function systemPrompt($request): string
    {
        return (string) ($request['messages'][0]['content'] ?? '');
    }

    /** Tool results sent back to the model in a recorded request, by tool call id order. */
    protected static function toolResults($request): array
    {
        return collect($request['messages'] ?? [])->where('role', 'tool')->map(fn ($m) => json_decode($m['content'], true))->values()->all();
    }
}
