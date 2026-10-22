<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\{DB, Storage};
use Livewire\Attributes\Locked;
use Livewire\Component;

class DatabaseMaintenance extends Component
{
    #[Locked] public $unusedFilesCount = 0;
    #[Locked] public $dbSize = null;
    #[Locked] public $databaseDriver = '';
    #[Locked] public $orphanedFiles = [];
    #[Locked] public $unusedBytes = 0;
    #[Locked] public $scanFailed = false;
    #[Locked] public $lastScannedAt = null;
    #[Locked] public $maintenanceMode = false;

    public function mount()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->maintenanceMode = (bool) Setting::get('maintenance_mode', false);
        $this->calculateStats();
    }

    public function calculateStats()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->databaseDriver = DB::connection()->getDriverName();
        try {
            $bytes = match ($this->databaseDriver) {
                'sqlite' => DB::selectOne('PRAGMA page_count')->page_count * DB::selectOne('PRAGMA page_size')->page_size,
                'mysql', 'mariadb' => DB::selectOne('SELECT COALESCE(SUM(data_length + index_length), 0) AS bytes FROM information_schema.TABLES WHERE table_schema = ?', [DB::connection()->getDatabaseName()])->bytes,
                'pgsql' => DB::selectOne('SELECT pg_database_size(current_database()) AS bytes')->bytes,
                default => null,
            };
            $this->dbSize = $bytes === null ? null : $this->formatBytes((int) $bytes);
        } catch (\Throwable) { $this->dbSize = null; }
        $this->detectUnusedFiles();
        $this->lastScannedAt = now()->toIso8601String();
    }

    private function detectUnusedFiles(): void
    {
        $this->orphanedFiles = [];
        $this->unusedBytes = 0;
        $this->unusedFilesCount = 0;
        $this->scanFailed = false;
        try {
            $references = DB::table('users')->whereNotNull('logo')->pluck('logo')->map(function ($logo) {
                return basename(rawurldecode(parse_url($logo, PHP_URL_PATH) ?: $logo));
            })->all();
            $disk = Storage::disk('public');
            foreach ($disk->files('logos') as $file) {
                if (str_starts_with(basename($file), '.') || in_array(basename($file), $references, true)) continue;
                $this->orphanedFiles[] = $file;
                $this->unusedBytes += $disk->size($file);
            }
            $this->unusedFilesCount = count($this->orphanedFiles);
        } catch (\Throwable) {
            $this->scanFailed = true;
            $this->orphanedFiles = [];
            $this->unusedBytes = 0;
        }
    }

    public function cleanStorage()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->resetErrorBag('cleanup');
        $this->detectUnusedFiles();
        if ($this->scanFailed) {
            $this->addError('cleanup', __('Storage could not be scanned. No files were deleted.'));
            return;
        }
        $deleted = 0;
        try {
            foreach ($this->orphanedFiles as $file) {
                if (!Storage::disk('public')->delete($file)) continue;
                $deleted++;
            }
        } catch (\Throwable) {
            $this->addError('cleanup', __('Some files could not be removed. Refresh the scan before trying again.'));
        }
        $this->calculateStats();
        session()->flash('storage_result', __(':value1 unused logo files removed.', ['value1' => $deleted]));
        $this->dispatch('storage-cleaned');
    }

    public function toggleMaintenance()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->maintenanceMode = !(bool) Setting::get('maintenance_mode', false);
        Setting::set('maintenance_mode', $this->maintenanceMode);
        session()->flash('maintenance_result', $this->maintenanceMode ? __('Maintenance mode is on. Member access is restricted.') : __('Maintenance mode is off. Member access is available.'));
        $this->dispatch('maintenance-updated');
    }

    public function formatBytes(int $bytes): string
    {
        return $bytes >= 1048576 ? number_format($bytes / 1048576, 2).' MB' : number_format($bytes / 1024, 1).' KB';
    }

    public function render()
    {
        return view('livewire.admin.database-maintenance', ['unusedSize' => $this->formatBytes($this->unusedBytes)]);
    }
}
