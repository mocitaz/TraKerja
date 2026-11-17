<?php

namespace App\Console\Commands;

use App\Helpers\CategoryHelper;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Asks the job-data model (gpt-5-nano, flex tier) for label suggestions on every golden-set
 * sample, so reviewers confirm or fix values instead of typing them. Suggestions are stored
 * next to the label (`suggestion`), never written into it: the label stays what a person approved.
 *
 * Doubles as the first real cost measurement for AI extraction (TraKerja 2.0 Blueprint, Fase 0).
 */
class SuggestGoldenLabels extends Command
{
    protected $signature = 'jobs:golden-suggest
        {--source=* : Only these sources}
        {--force : Ask again even when a suggestion for the same text exists}
        {--concurrency=6 : Parallel requests}';

    protected $description = 'Add AI label suggestions (gpt-5-nano) to golden-set samples and report the real cost';

    /** USD per 1M tokens, standard tier. Flex and Batch are half. Third-party figures; verify on OpenAI's pricing page. */
    private const PRICES = [
        'gpt-5-nano' => ['input' => 0.05, 'cached' => 0.005, 'output' => 0.40],
        'gpt-5-mini' => ['input' => 0.25, 'cached' => 0.025, 'output' => 2.00],
    ];

    private const MAX_TEXT_CHARS = 2500;

    public function handle(): int
    {
        $config = config('services.openai.jobs');
        if (empty($config['key'])) {
            $this->error('OPENAI_JOBS_API_KEY belum diisi di .env.');

            return self::FAILURE;
        }

        $disk = Storage::disk('local');
        $files = collect($disk->files('golden/labels'))
            ->reject(fn ($f) => str_starts_with(basename($f), '_'))
            ->filter(fn ($f) => ! $this->option('source') || in_array(basename($f, '.json'), $this->option('source'), true));

        $spent = 0.0;
        $calls = 0;
        $tokens = ['input' => 0, 'cached' => 0, 'output' => 0];
        $budget = (float) $config['daily_budget_usd'];

        foreach ($files as $file) {
            $rows = json_decode($disk->get($file), true) ?? [];
            $todo = [];
            foreach ($rows as $i => $row) {
                $text = $this->sampleText($row);
                $hash = hash('sha256', $text);
                if (! $this->option('force') && ($row['suggestion_meta']['input_hash'] ?? null) === $hash) {
                    continue;
                }
                $todo[$i] = ['text' => $text, 'hash' => $hash];
            }

            $this->info(basename($file, '.json').': '.count($todo).' sampel diminta saran');

            foreach (array_chunk($todo, (int) $this->option('concurrency'), true) as $chunk) {
                if ($spent >= $budget) {
                    $this->warn(sprintf('Anggaran harian $%.2f tercapai, berhenti.', $budget));
                    break 2;
                }

                $responses = Http::pool(fn (Pool $pool) => collect($chunk)->map(
                    fn ($item, $i) => $pool->as((string) $i)
                        ->withToken($config['key'])
                        ->timeout(180)
                        ->post('https://api.openai.com/v1/chat/completions', $this->payload($config, $item['text']))
                )->all());

                foreach ($chunk as $i => $item) {
                    $response = $responses[(string) $i] ?? null;
                    if (! $response instanceof \Illuminate\Http\Client\Response || ! $response->successful()) {
                        $this->warn('  gagal: '.$rows[$i]['id'].' '.Str::limit($response instanceof \Illuminate\Http\Client\Response ? $response->body() : 'timeout', 150));

                        continue;
                    }

                    $usage = $response->json('usage', []);
                    $cached = (int) Arr::get($usage, 'prompt_tokens_details.cached_tokens', 0);
                    $input = (int) ($usage['prompt_tokens'] ?? 0) - $cached;
                    $output = (int) ($usage['completion_tokens'] ?? 0);
                    $cost = $this->cost($config['model'], $input, $cached, $output, $response->json('service_tier'));

                    $calls++;
                    $spent += $cost;
                    $tokens['input'] += $input;
                    $tokens['cached'] += $cached;
                    $tokens['output'] += $output;

                    $rows[$i]['suggestion'] = json_decode((string) $response->json('choices.0.message.content'), true);
                    $rows[$i]['suggestion_meta'] = [
                        'model' => $config['model'], 'service_tier' => $response->json('service_tier'),
                        'input_hash' => $item['hash'], 'cost_usd' => round($cost, 7), 'created_at' => now()->toIso8601String(),
                    ];
                }

                $disk->put($file, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        }

        $perJob = $calls ? $spent / $calls : 0;
        $this->newLine();
        $this->info(sprintf('%d panggilan, biaya ±$%.4f (±$%.6f per loker).', $calls, $spent, $perJob));
        $this->line(sprintf('Token: input %d, cached %d, output %d.', $tokens['input'], $tokens['cached'], $tokens['output']));
        if ($perJob > 0) {
            $this->line(sprintf('Proyeksi: 1.000 loker butuh AI per hari ≈ $%.3f/hari.', $perJob * 1000));
        }

        return self::SUCCESS;
    }

    private function payload(array $config, string $text): array
    {
        $nullable = fn (string $type, array $extra = []) => array_merge(['type' => [$type, 'null']], $extra);

        $properties = [
            'sector' => ['type' => 'string', 'enum' => array_merge(CategoryHelper::getSektors(), ['Lainnya'])],
            'seniority' => ['type' => 'string', 'enum' => CheckGoldenJobs::ENUMS['seniority']],
            'education_min' => $nullable('string', ['enum' => array_merge(CheckGoldenJobs::ENUMS['education_min'], [null])]),
            'employment_type' => $nullable('string', ['enum' => array_merge(CheckGoldenJobs::ENUMS['employment_type'], [null])]),
            'work_mode' => $nullable('string', ['enum' => array_merge(CheckGoldenJobs::ENUMS['work_mode'], [null])]),
            'is_internship' => ['type' => 'boolean'],
            'city' => $nullable('string'),
            'province' => $nullable('string'),
            'country' => $nullable('string', ['description' => 'ISO 3166-1 alpha-2, contoh ID']),
            'salary_min' => $nullable('integer'),
            'salary_max' => $nullable('integer'),
            'salary_currency' => $nullable('string'),
            'salary_period' => $nullable('string', ['enum' => ['hour', 'day', 'week', 'month', 'year', null]]),
            'valid_through' => $nullable('string', ['description' => 'YYYY-MM-DD']),
        ];

        return [
            'model' => $config['model'],
            'service_tier' => $config['service_tier'],
            'reasoning_effort' => 'minimal',
            'messages' => [
                ['role' => 'system', 'content' => "Kamu memberi label data lowongan kerja. Isi sesuai skema JSON.\n"
                    ."Aturan: jangan mengarang. Field faktual (kota, provinsi, negara, gaji, batas lamar, pendidikan) diisi null bila tidak tertulis.\n"
                    ."sector dan seniority boleh disimpulkan dari isi lowongan. Gaji dalam angka penuh sesuai mata uang aslinya.\n"
                    .'is_internship true hanya untuk magang, internship, PKL, atau trainee program.'],
                ['role' => 'user', 'content' => $text],
            ],
            'response_format' => ['type' => 'json_schema', 'json_schema' => ['name' => 'job_label', 'strict' => true, 'schema' => [
                'type' => 'object', 'additionalProperties' => false,
                'required' => array_keys($properties), 'properties' => $properties,
            ]]],
        ];
    }

    /** Compact text for the model: title, company, location hints and the description, without HTML. */
    private function sampleText(array $row): string
    {
        $raw = json_decode(Storage::disk('local')->get("golden/raw/{$row['source']}/{$row['id']}.json") ?? '{}', true)['raw'] ?? [];
        $label = $row['label'] ?? [];

        $description = '';
        foreach (['description', 'descriptionPlain', 'content', 'jobDescription', 'job.description', 'job.qualifications'] as $key) {
            $value = Arr::get($raw, $key);
            if (is_string($value) && $value !== '') {
                $description .= "\n".$value;
            }
        }
        $description = trim(preg_replace('/\s+/u', ' ', strip_tags(html_entity_decode(html_entity_decode($description)))));

        return Str::limit(implode("\n", array_filter([
            'Judul: '.($label['title'] ?? ''),
            'Perusahaan: '.($label['company'] ?? ''),
            ($label['location_text'] ?? $label['city'] ?? null) ? 'Lokasi: '.($label['location_text'] ?? $label['city']) : null,
            'Deskripsi: '.$description,
        ])), self::MAX_TEXT_CHARS, '');
    }

    private function cost(string $model, int $input, int $cached, int $output, ?string $tier): float
    {
        $p = self::PRICES[$model] ?? self::PRICES['gpt-5-nano'];
        $usd = ($input * $p['input'] + $cached * $p['cached'] + $output * $p['output']) / 1_000_000;

        return in_array($tier, ['flex', 'batch'], true) ? $usd / 2 : $usd;
    }
}
