<?php

namespace App\Console\Commands;

use App\Jobs\ProcessAiPhoto;
use App\Models\AiPhoto;
use App\Services\AiPhotoProcessingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RecoverAiPhotos extends Command
{
    protected $signature = 'ai-photos:recover {--cleanup : Remove terminal and orphan source files older than seven days}';
    protected $description = 'Recover queued AI photos, refund expired processing and remove retained sources';

    public function handle(AiPhotoProcessingService $service): int
    {
        AiPhoto::where('status', 'processing')->where('processing_expires_at', '<=', now())->eachById(function ($photo) use ($service) {
            $service->expire($photo->id);
        });
        AiPhoto::where('status', 'queued')->where('created_at', '<=', now()->subMinutes(30))->eachById(function ($photo) use ($service) {
            $service->expire($photo->id);
        });
        AiPhoto::where('status', 'queued')->where('created_at', '<=', now()->subMinutes(2))->eachById(function ($photo) {
            ProcessAiPhoto::dispatch($photo->id);
        });
        if (!$this->option('cleanup')) return self::SUCCESS;
        AiPhoto::whereIn('status', ['completed', 'failed'])->where('updated_at', '<', now()->subDays(7))->whereNotNull('source_path')->eachById(function ($photo) {
            Storage::disk('local')->delete($photo->source_path);
            $photo->forceFill(['source_path' => null])->save();
        });
        $disk = Storage::disk('local');
        foreach ($disk->files('ai-photo-sources') as $path) {
            if ($disk->lastModified($path) < now()->subDays(7)->timestamp && !AiPhoto::where('source_path', $path)->exists()) {
                $disk->delete($path);
            }
        }
        return self::SUCCESS;
    }
}
