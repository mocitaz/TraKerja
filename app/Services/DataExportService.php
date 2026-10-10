<?php

namespace App\Services;

use App\Jobs\BuildDataExport;
use App\Models\DataExport;
use App\Models\JobApplication;
use App\Models\User;
use App\Support\JobApplicationCsv;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataExportService
{
    public const QUEUE_THRESHOLD = 500;
    public const USER_HEADERS = ['ID', 'Nama', 'Email', 'Status Premium', 'AI Trial', 'Role', 'Tanggal Daftar', 'Terverifikasi'];

    public static function userRow(User $user): array
    {
        return array_map(JobApplicationCsv::text(...), [$user->id, $user->name, $user->email, $user->is_premium ? 'Premium' : 'Free', $user->has_used_ai_analyzer_trial ? 'Sudah' : 'Belum', $user->isAdmin() ? 'Admin' : 'User', $user->created_at?->format('d M Y H:i') ?? '-', $user->email_verified_at ? 'Ya' : 'Tidak']);
    }

    public function queue(User $user, string $kind, array $filters = []): DataExport
    {
        abort_unless(in_array($kind, ['jobs', 'users'], true), 422);
        abort_if($kind === 'users' && !$user->isAdmin(), 403);
        $filters = Validator::make($filters, ['search' => 'sometimes|string|max:255', 'premium' => 'sometimes|in:all,premium,free', 'ai_trial' => 'sometimes|in:all,used,not_used'])->validate();
        $created = false;
        $export = DB::transaction(function () use ($user, $kind, $filters, &$created) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $query = $kind === 'users' ? UserExportQuery::build($filters) : JobApplication::where('user_id', $user->id);
            $maxId = $query->max('id') ?? 0;
            foreach (DataExport::where('user_id', $user->id)->where('kind', $kind)->where('max_source_id', $maxId)->whereIn('status', ['queued', 'processing'])->where('expires_at', '>', now())->lazyById(100) as $pending) {
                if ($pending->filters === $filters) return $pending;
            }
            $created = true;
            return DataExport::create(['user_id' => $user->id, 'kind' => $kind, 'filters' => $filters, 'max_source_id' => $maxId, 'expires_at' => now()->addDay()]);
        });
        if (!$created) return $export;
        try {
            BuildDataExport::dispatch($export->id)->afterCommit();
        } catch (\Throwable $exception) {
            $export->update(['status' => 'failed']);
            Log::warning('Data export dispatch failed', ['export_id' => $export->id, 'exception' => get_class($exception)]);
        }
        return $export;
    }
}
