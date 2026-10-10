<?php

namespace App\Http\Controllers;

use App\Models\DataExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataExportController extends Controller
{
    private function owned(Request $request, int $id): DataExport
    {
        $export = DataExport::where('user_id', $request->user()->id)->findOrFail($id);
        abort_if($export->kind === 'users' && !$request->user()->isAdmin(), 403);
        return $export;
    }

    public function show(Request $request, int $id)
    {
        return response()->view('data-exports.show', ['export' => $this->owned($request, $id)])->header('Cache-Control', 'private, no-store');
    }

    public function download(Request $request, int $id)
    {
        $export = $this->owned($request, $id);
        abort_if($export->expires_at->isPast(), 410, 'Ekspor kedaluwarsa. Buat ekspor baru.');
        abort_unless($export->status === 'ready' && $export->path && Storage::disk('local')->exists($export->path), 409, 'Ekspor belum tersedia.');
        return Storage::disk('local')->download($export->path, 'TraKerja_'.$export->kind.'_'.$export->id.'.csv', ['Content-Type' => 'text/csv; charset=UTF-8', 'Cache-Control' => 'private, no-store']);
    }
}
