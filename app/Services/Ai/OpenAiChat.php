<?php

namespace App\Services\Ai;

use App\Services\NaraClientStopped;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * The OpenAI client for TraKerja's user-facing AI (Nara, Nara Admin, AI Studio, email templates, copilot).
 * Same API and model family as the job engine (Chat Completions, gpt-5-mini / gpt-5-nano), same key by default.
 * It does not write to ai_calls, so these calls never eat into the job engine's daily budget.
 */
final class OpenAiChat
{
    public const URL = 'https://api.openai.com/v1/chat/completions';

    public static function key(): ?string
    {
        return config('services.openai.key') ?: null;
    }

    public static function model(): string
    {
        return (string) config('services.openai.model', 'gpt-5-mini');
    }

    public static function fallbackModel(): ?string
    {
        return config('services.openai.fallback_model') ?: null;
    }

    /** @return array<int, string> the default model, then its fallback */
    public static function models(?string $first = null): array
    {
        return array_values(array_unique(array_filter([$first, self::model(), self::fallbackModel()])));
    }

    /**
     * One request to one model; text is streamed to $onChunk when it is given. Never throws, except NaraClientStopped
     * (the user pressed Stop), which the caller handles.
     *
     * @param  array<string, mixed>  $payload  Chat Completions body without "model" (messages, tools, tool_choice, response_format…)
     * @return array{ok: bool, text: string, calls: array<int, array{id: string, name: string, args: array, arguments: string}>, emitted: bool, status: ?int, finish: ?string, refusal: ?string}
     */
    public function call(string $model, array $payload, ?callable $onChunk = null, int $timeout = 60): array
    {
        $out = ['ok' => false, 'text' => '', 'calls' => [], 'emitted' => false, 'status' => null, 'finish' => null, 'refusal' => null];
        $key = self::key();
        if (! $key) {
            return $out;
        }
        $payload = ['model' => $model] + $payload;
        try {
            if ($onChunk) {
                $payload['stream'] = true;
                $response = Http::withToken($key)->withOptions(['stream' => true])->connectTimeout(10)->timeout($timeout)->post(self::URL, $payload);
                $out['status'] = $response->status();
                if (! $response->successful()) {
                    Log::warning("OpenAI {$model} status {$response->status()}: ".mb_substr(self::scrub($response->body()), 0, 300));

                    return $out;
                }
                $this->readStream($response, $out, $onChunk);
            } else {
                $response = Http::withToken($key)->connectTimeout(10)->timeout($timeout)->post(self::URL, $payload);
                $out['status'] = $response->status();
                if (! $response->successful()) {
                    Log::warning("OpenAI {$model} status {$response->status()}: ".mb_substr(self::scrub($response->body()), 0, 300));

                    return $out;
                }
                $this->absorbMessage((array) $response->json(), $out);
            }
        } catch (NaraClientStopped $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::warning("OpenAI {$model} error: ".self::scrub($e->getMessage()));

            return $out;
        }
        // A reply needs text or a tool call; an empty answer (refused, cut off) lets the next model try.
        $out['ok'] = trim($out['text']) !== '' || $out['calls'] !== [];

        return $out;
    }

    /** Try the models in order. Stops after a model that already sent text to the user (it would show twice). */
    public function callModels(array $models, array $payload, ?callable $onChunk = null, int $timeout = 60): array
    {
        $last = ['ok' => false, 'text' => '', 'calls' => [], 'emitted' => false, 'status' => null, 'finish' => null, 'refusal' => null];
        foreach ($models as $model) {
            $last = $this->call($model, $payload, $onChunk, $timeout);
            if ($last['ok'] || $last['emitted']) {
                return $last;
            }
        }

        return $last;
    }

    private function absorbMessage(array $json, array &$out): void
    {
        $message = (array) data_get($json, 'choices.0.message', []);
        $out['finish'] = data_get($json, 'choices.0.finish_reason');
        $out['refusal'] = $message['refusal'] ?? null;
        $out['text'] = is_string($message['content'] ?? null) ? $message['content'] : '';
        foreach ((array) ($message['tool_calls'] ?? []) as $call) {
            $this->addCall($out, (string) ($call['id'] ?? ''), (string) data_get($call, 'function.name', ''), (string) data_get($call, 'function.arguments', ''));
        }
    }

    /** Server-sent events: text deltas go to the user as they come; tool calls arrive in pieces and are joined. */
    private function readStream($response, array &$out, callable $onChunk): void
    {
        $body = $response->toPsrResponse()->getBody();
        $buffer = '';
        $raw = '';
        $sawEvent = false;
        $pending = []; // tool calls by index: id, name, arguments
        $handle = function (string $line) use (&$out, &$pending, &$sawEvent, $onChunk) {
            if (! str_starts_with($line, 'data: ')) {
                return false;
            }
            $data = substr($line, 6);
            if ($data === '[DONE]') {
                return true;
            }
            $json = json_decode($data, true);
            if (! is_array($json)) {
                return false;
            }
            $sawEvent = true;
            $choice = (array) data_get($json, 'choices.0', []);
            $delta = (array) ($choice['delta'] ?? []);
            if (is_string($delta['content'] ?? null) && $delta['content'] !== '') {
                $out['text'] .= $delta['content'];
                $out['emitted'] = true;
                $onChunk($delta['content']);
            }
            if (is_string($delta['refusal'] ?? null)) {
                $out['refusal'] = ($out['refusal'] ?? '').$delta['refusal'];
            }
            foreach ((array) ($delta['tool_calls'] ?? []) as $piece) {
                $i = (int) ($piece['index'] ?? 0);
                $pending[$i]['id'] = ($pending[$i]['id'] ?? '') ?: (string) ($piece['id'] ?? '');
                $pending[$i]['name'] = ($pending[$i]['name'] ?? '').(string) data_get($piece, 'function.name', '');
                $pending[$i]['arguments'] = ($pending[$i]['arguments'] ?? '').(string) data_get($piece, 'function.arguments', '');
            }
            if (! empty($choice['finish_reason'])) {
                $out['finish'] = $choice['finish_reason'];
            }

            return false;
        };

        while (! $body->eof()) {
            $data = $body->read(4096);
            if ($data === '' || $data === false) {
                $body->eof() ? null : usleep(25000);

                continue;
            }
            $buffer .= $data;
            if (strlen($raw) < 200000) {
                $raw .= $data;
            }
            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = trim(substr($buffer, 0, $pos));
                $buffer = substr($buffer, $pos + 1);
                if ($handle($line)) {
                    break 2;
                }
            }
        }
        if (trim($buffer) !== '') {
            $handle(trim($buffer));
        }
        // A plain JSON body where a stream was expected (some proxies, test doubles): read it as one message.
        if (! $sawEvent && ($json = json_decode($raw, true)) && is_array($json)) {
            $this->absorbMessage($json, $out);
            if ($out['text'] !== '') {
                $out['emitted'] = true;
                $onChunk($out['text']);
            }

            return;
        }
        ksort($pending);
        foreach ($pending as $call) {
            $this->addCall($out, (string) ($call['id'] ?? ''), (string) ($call['name'] ?? ''), (string) ($call['arguments'] ?? ''));
        }
    }

    private function addCall(array &$out, string $id, string $name, string $arguments): void
    {
        if ($name === '') {
            return;
        }
        $args = json_decode($arguments !== '' ? $arguments : '{}', true);
        $out['calls'][] = ['id' => $id !== '' ? $id : 'call_'.count($out['calls']), 'name' => $name, 'args' => is_array($args) ? $args : [], 'arguments' => $arguments !== '' ? $arguments : '{}'];
    }

    /** The assistant turn that asked for tools; it goes back into the conversation before the tool results. */
    public static function assistantToolMessage(array $out): array
    {
        return ['role' => 'assistant', 'content' => $out['text'] !== '' ? $out['text'] : null, 'tool_calls' => array_map(fn ($call) => [
            'id' => $call['id'], 'type' => 'function', 'function' => ['name' => $call['name'], 'arguments' => $call['arguments']],
        ], $out['calls'])];
    }

    public static function toolResult(string $id, mixed $result): array
    {
        return ['role' => 'tool', 'tool_call_id' => $id, 'content' => json_encode($result, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE) ?: '{}'];
    }

    /**
     * Function declarations written as {name, description, parameters} (JSON schema; upper-case types are accepted)
     * to OpenAI tools. Accepts a flat list or the [['functionDeclarations' => [...]]] grouping.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function tools(array $declarations): array
    {
        if (isset($declarations[0]['functionDeclarations'])) {
            $declarations = array_merge(...array_map(fn ($group) => $group['functionDeclarations'] ?? [], $declarations));
        }

        return array_map(fn (array $d) => ['type' => 'function', 'function' => array_filter([
            'name' => $d['name'],
            'description' => $d['description'] ?? null,
            'parameters' => self::schema($d['parameters'] ?? ['type' => 'object', 'properties' => []]),
        ], fn ($v) => $v !== null)], $declarations);
    }

    private static function schema(mixed $node): mixed
    {
        if (! is_array($node)) {
            return $node;
        }
        $clean = [];
        foreach ($node as $key => $value) {
            if ($key === 'type' && is_string($value)) {
                $clean[$key] = strtolower($value);
            } elseif ($key === 'properties') {
                $props = [];
                foreach ((array) $value as $name => $prop) {
                    $props[$name] = self::schema($prop);
                }
                $clean[$key] = $props ?: new \stdClass;
            } else {
                $clean[$key] = is_array($value) ? self::schema($value) : $value;
            }
        }
        if (($clean['type'] ?? null) === 'object' && ! isset($clean['properties'])) {
            $clean['properties'] = new \stdClass;
        }

        return $clean;
    }

    /** Remove the API key from text that may end up in logs. */
    public static function scrub(string $text): string
    {
        $key = self::key();

        return $key ? str_replace($key, '***', $text) : $text;
    }
}
