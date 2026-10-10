<?php

namespace App\Console\Commands;

use App\Jobs\BuildDataExport;
use App\Models\DataExport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CleanupDataExports extends Command
{
    protected $signature = 'data-exports:cleanup';
    protected $description = 'Expire private export files and recover abandoned export work';

    public function handle(): int
    {
        DataExport::where(fn ($q) => $q->where('expires_at', '<=', now())->orWhere(fn ($q) => $q->where('status', 'processing')->where('updated_at', '<=', now()->subMinutes(10))))->eachById(function ($export) {
            DB::transaction(function () use ($export) {
                $locked = DataExport::whereKey($export->id)->lockForUpdate()->first();
                if (!$locked || ($locked->expires_at->isFuture() && !($locked->status === 'processing' && $locked->updated_at->lte(now()->subMinutes(10))))) return;
                Storage::disk('local')->delete($locked->path ?? 'data-exports/'.$locked->id.'.csv');
                $locked->update(['status' => 'failed', 'path' => null]);
            });
        });
        DataExport::where('status', 'queued')->where('expires_at', '>', now())->where('created_at', '<=', now()->subMinutes(2))->eachById(fn ($export) => BuildDataExport::dispatch($export->id));
        $disk = Storage::disk('local');
        foreach ($disk->files('data-exports') as $path) {
            if ($disk->lastModified($path) < now()->subDay()->timestamp && !DataExport::where('path', $path)->exists()) $disk->delete($path);
        }
        return self::SUCCESS;
    }
}
