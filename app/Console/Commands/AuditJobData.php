<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Read-only audit of both job-data stores before the TraKerja 2.0 unified schema (Blueprint Fase 0):
 * Laravel's job_postings and the asep bot's lowongan / grup_telegram / log_pengiriman tables.
 */
class AuditJobData extends Command
{
    protected $signature = 'jobs:audit {--json : Print the report as JSON}';

    protected $description = 'Read-only audit of job_postings (Laravel) and asep tables before the unified schema';

    public function handle(): int
    {
        $report = [
            'generated_at' => now('Asia/Jakarta')->toDateTimeString(),
            'connection' => [
                'driver' => DB::connection()->getDriverName(),
                'database' => DB::connection()->getDatabaseName(),
            ],
            'laravel_job_postings' => $this->auditJobPostings(),
            'asep_lowongan' => $this->auditLowongan(),
            'asep_grup_telegram' => $this->auditGrupTelegram(),
            'asep_log_pengiriman' => $this->auditLogPengiriman(),
        ];

        if ($this->option('json')) {
            $this->line(json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            return self::SUCCESS;
        }

        $this->render($report);

        return self::SUCCESS;
    }

    private function auditJobPostings(): array
    {
        if (! Schema::hasTable('job_postings')) {
            return ['exists' => false];
        }

        $sourceNames = Schema::hasTable('scraper_sources')
            ? DB::table('scraper_sources')->pluck('name', 'id')->all()
            : [];

        $bySource = DB::table('job_postings')
            ->select('scraper_source_id', DB::raw('count(*) as total'))
            ->groupBy('scraper_source_id')
            ->pluck('total', 'scraper_source_id')
            ->mapWithKeys(fn ($total, $id) => [($sourceNames[$id] ?? "source #{$id}") => (int) $total])
            ->all();

        return [
            'exists' => true,
            'total' => DB::table('job_postings')->count(),
            'by_status' => $this->countBy('job_postings', 'status'),
            'by_source' => $bySource,
            'created_range' => $this->range('job_postings', 'created_at'),
            'never_validated' => DB::table('job_postings')->whereNull('last_validated_at')->count(),
            'duplicates' => $this->duplicates('job_postings', 'title', 'company_name', 'scraper_source_id'),
        ];
    }

    private function auditLowongan(): array
    {
        if (! Schema::hasTable('lowongan')) {
            return ['exists' => false, 'note' => 'Tabel lowongan tidak ada di koneksi ini; asep mungkin memakai database lain.'];
        }

        $columns = Schema::getColumnListing('lowongan');
        $report = [
            'exists' => true,
            'columns' => $columns,
            'total' => DB::table('lowongan')->count(),
        ];

        foreach (['status_kirim', 'tipe_kerja', 'kategori', 'sumber_platform'] as $column) {
            if (in_array($column, $columns, true)) {
                $report['by_'.$column] = $this->countBy('lowongan', $column);
            }
        }

        if (in_array('created_at', $columns, true)) {
            $report['created_range'] = $this->range('lowongan', 'created_at');
        }

        if (in_array('judul', $columns, true) && in_array('perusahaan', $columns, true)) {
            $sourceColumn = in_array('sumber_platform', $columns, true) ? 'sumber_platform' : null;
            $report['duplicates'] = $this->duplicates('lowongan', 'judul', 'perusahaan', $sourceColumn);
        }

        return $report;
    }

    private function auditGrupTelegram(): array
    {
        if (! Schema::hasTable('grup_telegram')) {
            return ['exists' => false];
        }

        return [
            'exists' => true,
            'columns' => Schema::getColumnListing('grup_telegram'),
            'total' => DB::table('grup_telegram')->count(),
            'aktif' => Schema::hasColumn('grup_telegram', 'aktif')
                ? DB::table('grup_telegram')->where('aktif', 1)->count()
                : null,
        ];
    }

    private function auditLogPengiriman(): array
    {
        if (! Schema::hasTable('log_pengiriman')) {
            return ['exists' => false];
        }

        $report = [
            'exists' => true,
            'columns' => Schema::getColumnListing('log_pengiriman'),
            'total' => DB::table('log_pengiriman')->count(),
        ];

        if (Schema::hasColumn('log_pengiriman', 'status')) {
            $report['by_status'] = $this->countBy('log_pengiriman', 'status');
        }

        if (Schema::hasColumn('log_pengiriman', 'created_at')) {
            $report['last_14_days_by_status'] = DB::table('log_pengiriman')
                ->where('created_at', '>=', now()->subDays(14))
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->map(fn ($n) => (int) $n)
                ->all();
        }

        return $report;
    }

    private function countBy(string $table, string $column): array
    {
        return DB::table($table)
            ->select($column, DB::raw('count(*) as total'))
            ->groupBy($column)
            ->orderByDesc('total')
            ->pluck('total', $column)
            ->mapWithKeys(fn ($total, $key) => [($key === '' || $key === null ? '(kosong)' : (string) $key) => (int) $total])
            ->all();
    }

    private function range(string $table, string $column): array
    {
        return [
            'oldest' => DB::table($table)->min($column),
            'newest' => DB::table($table)->max($column),
        ];
    }

    /**
     * Estimate duplicates by normalized title + company: rows beyond the first in each group,
     * and how many of those groups span more than one source (cross-platform duplicates).
     */
    private function duplicates(string $table, string $titleColumn, string $companyColumn, ?string $sourceColumn): array
    {
        $groups = [];
        $select = array_filter(['id', $titleColumn, $companyColumn, $sourceColumn]);

        DB::table($table)->select($select)->lazyById(2000, 'id')->each(
            function ($row) use (&$groups, $titleColumn, $companyColumn, $sourceColumn) {
                $key = $this->normalize($row->{$titleColumn}).'|'.$this->normalize($row->{$companyColumn});
                $groups[$key]['n'] = ($groups[$key]['n'] ?? 0) + 1;
                if ($sourceColumn) {
                    $groups[$key]['sources'][(string) $row->{$sourceColumn}] = true;
                }
            }
        );

        $extraRows = 0;
        $duplicateGroups = 0;
        $crossSourceGroups = 0;
        foreach ($groups as $group) {
            if ($group['n'] > 1) {
                $duplicateGroups++;
                $extraRows += $group['n'] - 1;
                if (count($group['sources'] ?? []) > 1) {
                    $crossSourceGroups++;
                }
            }
        }

        return [
            'unique_title_company' => count($groups),
            'duplicate_groups' => $duplicateGroups,
            'extra_rows' => $extraRows,
            'cross_source_groups' => $sourceColumn ? $crossSourceGroups : null,
        ];
    }

    private function normalize(?string $value): string
    {
        $value = mb_strtolower((string) $value);
        $value = preg_replace('/\b(pt|tbk|persero|cv)\b\.?/u', ' ', $value);

        return preg_replace('/[^\p{L}\p{N}]+/u', '', $value) ?? '';
    }

    private function render(array $report): void
    {
        $this->info('Audit data loker · '.$report['generated_at']);
        $this->line('Koneksi: '.$report['connection']['driver'].' / '.$report['connection']['database']);

        foreach (['laravel_job_postings', 'asep_lowongan', 'asep_grup_telegram', 'asep_log_pengiriman'] as $section) {
            $this->newLine();
            $this->info('== '.$section);
            $data = $report[$section];
            if (! ($data['exists'] ?? false)) {
                $this->warn('Tabel tidak ditemukan. '.($data['note'] ?? ''));

                continue;
            }
            foreach ($data as $key => $value) {
                if ($key === 'exists') {
                    continue;
                }
                $this->line(str_pad($key, 24).': '.(is_array($value)
                    ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                    : (string) $value));
            }
        }

        $this->newLine();
        $this->comment('Kirim output --json dari server produksi untuk menyusun migrasi Fase 1.');
    }
}
