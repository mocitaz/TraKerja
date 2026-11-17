<?php

namespace App\JobEngine\Ai;

use App\Models\AiCall;
use Illuminate\Support\Facades\Http;

/**
 * Single entry point for OpenAI in the job engine (TraKerja 2.0 Blueprint, Fase 1).
 * - strict JSON-schema output (Structured Outputs);
 * - cache: the same input for the same purpose is never paid for twice;
 * - daily budget from config('services.openai.jobs.daily_budget_usd');
 * - flex tier by default; every call recorded in ai_calls.
 */
class AiGateway
{
    /** USD per 1M tokens, standard tier; flex and batch are half. Third-party figures, verify on openai.com/pricing. */
    public const PRICES = [
        'gpt-5-nano' => ['input' => 0.05, 'cached' => 0.005, 'output' => 0.40],
        'gpt-5-mini' => ['input' => 0.25, 'cached' => 0.025, 'output' => 2.00],
    ];

    /**
     * @param  array<string, mixed>  $schema  JSON schema for the answer (object)
     * @return array<string, mixed>|null  the parsed answer, or null when skipped or failed
     */
    public function structured(string $purpose, string $instructions, string $input, array $schema, array $context = [], bool $fallback = false): ?array
    {
        $config = config('services.openai.jobs');
        $model = $fallback ? $config['fallback_model'] : $config['model'];
        $hash = hash('sha256', $model.'|'.$instructions.'|'.$input.'|'.json_encode($schema));

        $cached = AiCall::where('input_hash', $hash)->where('purpose', $purpose)->where('status', 'ok')->latest('id')->first();
        if ($cached) {
            return $cached->response;
        }

        $base = ['purpose' => $purpose, 'model' => $model, 'input_hash' => $hash, 'service_tier' => $config['service_tier']]
            + array_intersect_key($context, array_flip(['vacancy_listing_id', 'crawl_run_id']));

        // No key (e.g. a server without AI): skip quietly so scraping never depends on AI.
        if (empty($config['key'])) {
            return null;
        }
        if ($this->spentToday() >= (float) $config['daily_budget_usd']) {
            AiCall::create($base + ['status' => 'budget_skipped']);

            return null;
        }

        $started = microtime(true);
        $response = Http::withToken($config['key'])->timeout(180)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'service_tier' => $config['service_tier'],
            'reasoning_effort' => 'minimal',
            'messages' => [['role' => 'system', 'content' => $instructions], ['role' => 'user', 'content' => $input]],
            'response_format' => ['type' => 'json_schema', 'json_schema' => ['name' => $purpose, 'strict' => true, 'schema' => $schema]],
        ]);
        $latency = (int) ((microtime(true) - $started) * 1000);

        if (! $response->successful()) {
            AiCall::create($base + ['status' => 'error', 'latency_ms' => $latency, 'response' => ['error' => mb_substr($response->body(), 0, 500)]]);

            return null;
        }

        $usage = $response->json('usage', []);
        $cachedTokens = (int) data_get($usage, 'prompt_tokens_details.cached_tokens', 0);
        $prompt = (int) ($usage['prompt_tokens'] ?? 0);
        $output = (int) ($usage['completion_tokens'] ?? 0);
        $answer = json_decode((string) $response->json('choices.0.message.content'), true);

        AiCall::create($base + [
            'service_tier' => $response->json('service_tier', $config['service_tier']),
            'prompt_tokens' => $prompt,
            'cached_tokens' => $cachedTokens,
            'output_tokens' => $output,
            'cost_usd' => self::cost($model, $prompt - $cachedTokens, $cachedTokens, $output, $response->json('service_tier')),
            'latency_ms' => $latency,
            'status' => is_array($answer) ? 'ok' : 'invalid_output',
            'response' => is_array($answer) ? $answer : null,
        ]);

        return is_array($answer) ? $answer : null;
    }

    public function spentToday(): float
    {
        return (float) AiCall::where('created_at', '>=', now()->startOfDay())->sum('cost_usd');
    }

    public static function cost(string $model, int $input, int $cached, int $output, ?string $tier): float
    {
        $p = self::PRICES[$model] ?? self::PRICES['gpt-5-nano'];
        $usd = ($input * $p['input'] + $cached * $p['cached'] + $output * $p['output']) / 1_000_000;

        return in_array($tier, ['flex', 'batch'], true) ? $usd / 2 : $usd;
    }
}
