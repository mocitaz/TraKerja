<?php

namespace App\Console\Commands;

use App\JobEngine\Adapters\GreenhouseAdapter;
use App\JobEngine\Adapters\KalibrrAdapter;
use App\JobEngine\Normalize\CompanyResolver;
use App\JobEngine\Normalize\IndonesianText;
use App\JobEngine\Normalize\RegionResolver;
use App\JobEngine\Parsing\JobPostingJsonLd;
use App\JobEngine\ScrapedJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Measures the engine's field accuracy on the golden set (TraKerja 2.0 Blueprint, Fase 2/3).
 * Rebuilds each sample from its stored raw data exactly as the engine would (adapter mapping +
 * Indonesian normalizers), without touching the database, and compares field by field.
 *
 * Only reviewed labels count by default. --drafts adds unreviewed ones (indicative only: drafts
 * come from the same structured data). --vs=suggestion compares with the AI suggestion instead.
 */
class EvaluateGoldenJobs extends Command
{
    protected $signature = 'jobs:golden-eval
        {--drafts : Include labels that are not reviewed yet}
        {--vs=label : Reference to compare with: label or suggestion}
        {--json : Print the report as JSON}';

    protected $description = 'Measure engine field accuracy against the golden set';

    public const FIELDS = ['title', 'company', 'region', 'employment_type', 'work_mode', 'seniority', 'education_min',
        'is_internship', 'salary_min', 'salary_max', 'date_posted', 'valid_through'];

    public function handle(RegionResolver $regions): int
    {
        $disk = Storage::disk('local');
        $stats = [];
        $examples = [];
        $evaluated = 0;
        $unsupported = [];

        foreach ($disk->files('golden/labels') as $file) {
            $source = basename($file, '.json');
            if (str_starts_with($source, '_')) {
                continue;
            }
            foreach (json_decode($disk->get($file), true) ?? [] as $row) {
                if (! ($row['reviewed'] ?? false) && ! $this->option('drafts')) {
                    continue;
                }
                $raw = json_decode($disk->get("golden/raw/{$source}/{$row['id']}.json") ?? 'null', true)['raw'] ?? null;
                $job = $raw ? $this->rebuild($source, $raw, $row['url']) : null;
                if (! $job) {
                    $unsupported[$source] = true;

                    continue;
                }

                $reference = $this->option('vs') === 'suggestion' ? array_merge($row['label'], $row['suggestion'] ?? []) : $row['label'];
                $engine = $this->engineView($job);
                $evaluated++;

                foreach (self::FIELDS as $field) {
                    [$expected, $actual] = $this->comparable($field, $reference, $engine, $regions);
                    if ($expected === null && $actual === null) {
                        continue;
                    }
                    $ok = $expected === $actual;
                    $stats[$field]['n'] = ($stats[$field]['n'] ?? 0) + 1;
                    $stats[$field]['ok'] = ($stats[$field]['ok'] ?? 0) + ($ok ? 1 : 0);
                    $stats[$field]['missing'] = ($stats[$field]['missing'] ?? 0) + ($actual === null ? 1 : 0);
                    if (! $ok && count($examples[$field] ?? []) < 3) {
                        $examples[$field][] = "{$row['id']}: harap ".json_encode($expected, JSON_UNESCAPED_UNICODE).', mesin '.json_encode($actual, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
        }

        $report = [
            'reference' => $this->option('vs'),
            'includes_drafts' => (bool) $this->option('drafts'),
            'samples' => $evaluated,
            'fields' => collect($stats)->map(fn ($s) => [
                'accuracy_pct' => round($s['ok'] / max($s['n'], 1) * 100, 1),
                'compared' => $s['n'],
                'engine_empty' => $s['missing'],
            ])->all(),
            'examples' => $examples,
            'unsupported_sources' => array_keys($unsupported),
        ];

        if ($this->option('json')) {
            $this->line(json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return self::SUCCESS;
        }

        if ($evaluated === 0) {
            $this->warn('Belum ada label yang direview. Review di Admin → Golden Set Review, atau pakai --drafts untuk gambaran awal.');

            return self::SUCCESS;
        }

        $this->info(sprintf('%d sampel dibandingkan dengan %s%s', $evaluated, $this->option('vs'), $this->option('drafts') ? ' (termasuk draf, hanya indikasi)' : ''));
        $this->table(['Field', 'Akurasi', 'Dibandingkan', 'Mesin kosong'], collect($report['fields'])->map(fn ($f, $k) => [$k, $f['accuracy_pct'].'%', $f['compared'], $f['engine_empty']])->values());
        foreach ($examples as $field => $lines) {
            $this->line("<comment>{$field}</comment>");
            foreach ($lines as $line) {
                $this->line('  '.$line);
            }
        }
        if ($unsupported) {
            $this->line('Sumber belum dievaluasi (belum ada pemetaan offline): '.implode(', ', array_keys($unsupported)));
        }

        return self::SUCCESS;
    }

    private function rebuild(string $source, array $raw, string $url): ?ScrapedJob
    {
        return match ($source) {
            'dealls', 'pintarnya' => JobPostingJsonLd::toScrapedJob($raw, $source, $url),
            'kalibrr' => isset($raw['job']) ? KalibrrAdapter::map($raw['job'], $url, $raw['isExpired'] ?? null) : null,
            'greenhouse' => GreenhouseAdapter::map($raw, explode('-', basename($url))[0] ?: 'xendit'),
            default => null,
        };
    }

    /** What the engine would store, minus anything that needs the database. */
    private function engineView(ScrapedJob $job): array
    {
        if ($job->salaryMin === null && ($salary = IndonesianText::salary($job->salaryText) ?? IndonesianText::salaryFromDescription($job->description))) {
            [$job->salaryMin, $job->salaryMax] = [$salary['min'], $salary['max']];
        }

        return [
            'title' => $job->title,
            'company' => $job->company ? CompanyResolver::displayName($job->company) : null,
            'city' => $job->city, 'province' => $job->province, 'location_text' => $job->locationText,
            'employment_type' => $job->employmentType,
            'work_mode' => $job->workMode,
            'seniority' => IndonesianText::seniority($job->title),
            'education_min' => $job->educationMin ?? IndonesianText::educationMin($job->description),
            'is_internship' => $job->employmentType === 'internship' || (bool) preg_match('/\b(magang|intern(ship)?|pkl|trainee)\b/i', (string) $job->title),
            'salary_min' => $job->salaryMin,
            'salary_max' => $job->salaryMax,
            'date_posted' => $job->datePosted,
            'valid_through' => $job->validThrough,
        ];
    }

    /** @return array{0: mixed, 1: mixed} expected and actual, normalized for a fair comparison */
    private function comparable(string $field, array $reference, array $engine, RegionResolver $regions): array
    {
        return match ($field) {
            'title' => [$this->text($reference['title'] ?? null), $this->text($engine['title'])],
            'company' => [
                ($reference['company'] ?? null) ? CompanyResolver::normalize($reference['company']) : null,
                $engine['company'] ? CompanyResolver::normalize($engine['company']) : null,
            ],
            'region' => [
                $regions->resolve($reference['city'] ?? null, $reference['province'] ?? null, $reference['location_text'] ?? null),
                $regions->resolve($engine['city'], $engine['province'], $engine['location_text']),
            ],
            'is_internship' => [isset($reference['is_internship']) ? (bool) $reference['is_internship'] : null, $engine['is_internship']],
            'salary_min', 'salary_max' => [
                isset($reference[$field]) ? (int) $reference[$field] : null,
                isset($engine[$field]) ? (int) $engine[$field] : null,
            ],
            default => [$reference[$field] ?? null, $engine[$field] ?? null],
        };
    }

    private function text(?string $value): ?string
    {
        return $value === null ? null : mb_strtolower(trim(preg_replace('/\s+/u', ' ', html_entity_decode($value))));
    }
}
