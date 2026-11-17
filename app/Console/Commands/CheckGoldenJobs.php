<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Reports the state of the golden set: size per source, review progress, field coverage,
 * and label values that break the agreed enums (TraKerja 2.0 Blueprint, Fase 0).
 */
class CheckGoldenJobs extends Command
{
    protected $signature = 'jobs:golden-check {--json : Print the report as JSON}';

    protected $description = 'Check golden-set labels: counts, review progress, field coverage and invalid values';

    public const ENUMS = \App\JobEngine\Vocabulary::ENUMS;

    /** Fields every reviewed label must fill in. */
    public const REQUIRED_WHEN_REVIEWED = ['title', 'company', 'employment_type', 'is_internship'];

    public function handle(): int
    {
        $disk = Storage::disk('local');
        $files = array_filter($disk->files('golden/labels'), fn ($f) => ! str_ends_with($f, '_duplicates.json'));

        if ($files === []) {
            $this->warn('Golden set kosong. Jalankan dulu: php artisan jobs:golden-collect');

            return self::FAILURE;
        }

        $perSource = [];
        $coverage = [];
        $problems = [];
        $total = 0;

        foreach ($files as $file) {
            $rows = json_decode($disk->get($file), true) ?? [];
            $source = basename($file, '.json');
            $reviewed = 0;

            foreach ($rows as $row) {
                $total++;
                $label = $row['label'] ?? [];
                if ($row['reviewed'] ?? false) {
                    $reviewed++;
                    foreach (self::REQUIRED_WHEN_REVIEWED as $field) {
                        if (($label[$field] ?? null) === null || ($label[$field] ?? '') === '') {
                            $problems[] = "{$row['id']}: {$field} kosong padahal reviewed=true";
                        }
                    }
                }
                foreach ($label as $field => $value) {
                    $coverage[$field] = ($coverage[$field] ?? 0) + (($value === null || $value === '') ? 0 : 1);
                    if (isset(self::ENUMS[$field]) && $value !== null && ! in_array($value, self::ENUMS[$field], true)) {
                        $problems[] = "{$row['id']}: {$field}='{$value}' tidak ada di enum";
                    }
                }
            }

            $perSource[$source] = ['samples' => count($rows), 'reviewed' => $reviewed];
        }

        $duplicates = $disk->exists('golden/labels/_duplicates.json')
            ? json_decode($disk->get('golden/labels/_duplicates.json'), true) ?? []
            : [];

        $report = [
            'total' => $total,
            'reviewed' => array_sum(array_column($perSource, 'reviewed')),
            'per_source' => $perSource,
            'field_coverage_pct' => array_map(fn ($n) => round($n / max($total, 1) * 100), $coverage),
            'duplicate_candidates' => count($duplicates),
            'duplicate_candidates_reviewed' => count(array_filter($duplicates, fn ($d) => $d['reviewed'] ?? false)),
            'problems' => $problems,
        ];

        if ($this->option('json')) {
            $this->line(json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return $problems === [] ? self::SUCCESS : self::FAILURE;
        }

        $this->info("Golden set: {$report['total']} sampel, {$report['reviewed']} sudah direview");
        $this->table(['Sumber', 'Sampel', 'Direview'], collect($perSource)->map(fn ($v, $k) => [$k, $v['samples'], $v['reviewed']])->values());
        $this->table(['Field', 'Terisi (%)'], collect($report['field_coverage_pct'])->map(fn ($v, $k) => [$k, $v])->values());
        $this->line("Kandidat duplikat: {$report['duplicate_candidates']} ({$report['duplicate_candidates_reviewed']} direview)");

        foreach (array_slice($problems, 0, 30) as $problem) {
            $this->warn($problem);
        }
        if (count($problems) > 30) {
            $this->warn('… dan '.(count($problems) - 30).' masalah lain (pakai --json).');
        }

        return $problems === [] ? self::SUCCESS : self::FAILURE;
    }
}
