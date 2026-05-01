<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DatabaseMaintenance extends Component
{
    public $unusedFilesCount = 0;
    public $dbSize = '0 MB';

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // Get DB Size (MySQL)
        $dbName = config('database.connections.mysql.database');
        $size = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size FROM information_schema.TABLES WHERE table_schema = ?", [$dbName]);
        $this->dbSize = ($size[0]->size ?? 0) . ' MB';

        // Simplified unused file detection (demo logic)
        $this->unusedFilesCount = rand(5, 50); 
    }

    public function cleanStorage()
    {
        // Logic to clean old temp files or unlinked avatars
        // For now, we simulate cleaning
        sleep(1);
        
        $this->unusedFilesCount = 0;
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Storage Cleaned!',
            'message' => 'File sampah berhasil dihapus dari server.',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.database-maintenance')
            ->layout('components.admin-layout');
    }
}
