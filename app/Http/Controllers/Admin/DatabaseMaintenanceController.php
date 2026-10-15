<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DatabaseMaintenanceController extends Controller
{
    public function downloadBackup()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        try {
            $path = app(\App\Services\DatabaseBackup::class)->create();
            Log::info('Admin downloaded database backup', ['admin_id' => Auth::id()]);
            return response()->download($path, 'trakerja_backup_'.now()->format('Y-m-d_H-i-s').'.sql')
                ->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            Log::error('Database backup failed', ['admin_id' => Auth::id()]);
            return back()->with('error', 'Backup database gagal dibuat. Tidak ada file yang diunduh. Hubungi administrator server.');
        }
    }
}
