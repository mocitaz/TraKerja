<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync storage/app/public to public/storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing storage files...');
        
        // Get all files from storage/app/public
        $files = Storage::disk('public')->allFiles();
        
        $synced = 0;
        foreach ($files as $file) {
            $sourcePath = storage_path('app/public/' . $file);
            $targetPath = public_path('storage/' . $file);
            $targetDir = dirname($targetPath);
            
            // Create target directory if it doesn't exist
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            // Copy file if it doesn't exist or is different
            if (!file_exists($targetPath) || filemtime($sourcePath) > filemtime($targetPath)) {
                copy($sourcePath, $targetPath);
                $synced++;
                $this->line("Synced: {$file}");
            }
        }
        
        $this->info("Sync completed. {$synced} files synced.");
        
        return 0;
    }
}