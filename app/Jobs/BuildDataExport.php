<?php

namespace App\Jobs;

use App\Models\DataExport;
use App\Models\JobApplication;
use App\Models\User;
use App\Services\DataExportService;
use App\Services\UserExportQuery;
use App\Support\JobApplicationCsv;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BuildDataExport implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $timeout = 240;
    public int $tries = 1;
    public bool $failOnTimeout = true;
    public int $uniqueFor = 600;

    public function __construct(public int $exportId)
    {
        $this->onConnection('exports')->onQueue('exports');
    }

    public function uniqueId(): string { return (string) $this->exportId; }

    public function handle(): void
    {
        $export = DB::transaction(function () {
            $export = DataExport::whereKey($this->exportId)->lockForUpdate()->first();
            if (!$export || $export->status !== 'queued') return null;
            $export->update(['status' => 'processing']);
            return $export;
        });
        if (!$export) return;
        $file = null;
        try {
            $user = User::find($export->user_id);
            if (!$user || $export->expires_at->isPast() || !in_array($export->kind, ['jobs', 'users'], true) || ($export->kind === 'users' && !$user->isAdmin())) {
                throw new \RuntimeException('Export access expired');
            }
            $path = 'data-exports/'.$export->id.'.csv';
            $disk = Storage::disk('local');
            if (!$disk->makeDirectory('data-exports')) throw new \RuntimeException('Export directory unavailable');
            $file = fopen($disk->path($path), 'wb');
            if (!$file) throw new \RuntimeException('Export file unavailable');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $export->kind === 'jobs' ? JobApplicationCsv::HEADERS : DataExportService::USER_HEADERS, ',', '"', '');
            $query = $export->kind === 'jobs' ? JobApplication::where('user_id', $user->id) : UserExportQuery::build($export->filters);
            $count = 0;
            foreach ($query->where('id', '<=', $export->max_source_id)->lazyById(500) as $row) {
                if (fputcsv($file, $export->kind === 'jobs' ? JobApplicationCsv::row($row) : DataExportService::userRow($row), ',', '"', '') === false) throw new \RuntimeException('Export write failed');
                $count++;
            }
            fclose($file);
            $file = null;
            $updated = DataExport::whereKey($export->id)->where('status', 'processing')->update(['status' => 'ready', 'path' => $path, 'row_count' => $count, 'updated_at' => now()]);
            if (!$updated) $disk->delete($path);
        } catch (\Throwable $exception) {
            if (is_resource($file)) fclose($file);
            $this->failed($exception);
            Log::warning('Data export failed', ['export_id' => $this->exportId, 'exception' => get_class($exception)]);
        }
    }

    public function failed(?\Throwable $exception): void
    {
        $updated = DataExport::whereKey($this->exportId)->whereIn('status', ['queued', 'processing'])->update(['status' => 'failed', 'updated_at' => now()]);
        if ($updated) Storage::disk('local')->delete('data-exports/'.$this->exportId.'.csv');
    }
}
