<?php

namespace App\Services;

use App\Jobs\ProcessAiPhoto;
use App\Models\AiPhoto;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AiPhotoProcessingService
{
    public function submit(User $user, UploadedFile $file, array $payload, string $key): AiPhoto
    {
        return $this->createRequest($user, $payload, $key, $file->getClientOriginalName(), fn () => $file->store('ai-photo-sources', 'local'));
    }

    public function retry(User $user, AiPhoto $source, string $key): AiPhoto
    {
        abort_unless($source->user_id === $user->id, 404);
        if ($source->status !== 'failed' || !$source->source_path || !Storage::disk('local')->exists($source->source_path)) {
            throw ValidationException::withMessages(['photo' => 'Foto sumber tidak tersedia untuk dicoba lagi. Unggah foto baru.']);
        }
        return $this->createRequest($user, $source->request_payload, $key, $source->original_photo_name, function () use ($source) {
            $path = 'ai-photo-sources/'.Str::uuid().'.'.pathinfo($source->source_path, PATHINFO_EXTENSION);
            return Storage::disk('local')->copy($source->source_path, $path) ? $path : false;
        });
    }

    private function createRequest(User $user, array $payload, string $key, string $name, callable $store): AiPhoto
    {
        $path = null;
        $created = false;
        try {
            $photo = DB::transaction(function () use ($user, $payload, $key, $name, $store, &$path, &$created) {
                $locked = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
                $existing = AiPhoto::where('user_id', $user->id)->where('request_key', $key)->first();
                if ($existing) return $existing;
                $metered = Setting::isMonetizationEnabled();
                if ($metered && User::whereKey($user->id)->where('photo_credits', '>', 0)->decrement('photo_credits') !== 1) {
                    throw ValidationException::withMessages(['photo' => 'Kredit Photo AI Anda telah habis.']);
                }
                $path = $store();
                if (!$path) throw new \RuntimeException('Could not store private photo source');
                $photo = new AiPhoto;
                $photo->forceFill([
                    'user_id' => $locked->id, 'type' => $payload['type'], 'request_key' => $key,
                    'status' => 'queued', 'credit_state' => $metered ? 'reserved' : 'none',
                    'source_path' => $path, 'request_payload' => $payload, 'original_photo_name' => $name,
                ])->save();
                $created = true;
                return $photo;
            });
        } catch (\Throwable $exception) {
            if ($path) Storage::disk('local')->delete($path);
            throw $exception;
        }
        if ($created) {
            // The persisted request also lets the recovery command repair a lost queue dispatch.
            try {
                ProcessAiPhoto::dispatch($photo->id)->afterCommit();
            } catch (\Throwable $exception) {
                $this->fail($photo->id, 'Pekerjaan tidak dapat masuk antrean. Silakan coba lagi.');
                Log::warning('AI photo dispatch failed', ['photo_id' => $photo->id, 'exception' => get_class($exception)]);
            }
        }
        return $photo->refresh();
    }

    public function process(int $id): void
    {
        $photo = DB::transaction(function () use ($id) {
            $photo = AiPhoto::whereKey($id)->lockForUpdate()->first();
            if (!$photo || $photo->status !== 'queued') return null;
            $photo->forceFill(['status' => 'processing', 'processing_token' => (string) Str::uuid(), 'processing_expires_at' => now()->addSeconds(330)])->save();
            return $photo;
        });
        if (!$photo) return;
        try {
            $payload = $photo->request_payload;
            $type = $payload['type'];
            unset($payload['type']);
            $stream = Storage::disk('local')->readStream($photo->source_path);
            if (!is_resource($stream)) throw new \RuntimeException('Photo source unavailable');
            try {
                $response = Http::connectTimeout(10)->timeout(300)->attach('photo', $stream, $photo->original_photo_name)
                    ->post(rtrim(config('services.photo_api.url'), '/').($type === 'remove_bg' ? '/remove-bg' : '/enhance-photo-ai'), $payload);
            } finally {
                if (is_resource($stream)) fclose($stream);
            }
            $data = $response->json();
            if (!$response->successful() || !is_array($data) || ($data['success'] ?? false) !== true
                || !is_string($data['result_url'] ?? null) || strlen($data['result_url']) > 255
                || !filter_var($data['result_url'], FILTER_VALIDATE_URL)
                || !in_array(parse_url($data['result_url'], PHP_URL_SCHEME), ['http', 'https'], true)) {
                throw new \RuntimeException('Provider returned no valid result');
            }
            $completed = DB::transaction(function () use ($photo, $data) {
                $locked = AiPhoto::whereKey($photo->id)->lockForUpdate()->first();
                if (!$locked || $locked->status !== 'processing' || $locked->processing_token !== $photo->processing_token) return false;
                $locked->forceFill([
                    'status' => 'completed', 'credit_state' => $locked->credit_state === 'reserved' ? 'consumed' : $locked->credit_state,
                    'result_url' => $data['result_url'], 'style_used' => $data['style_used'] ?? null,
                    'background_used' => $data['background'] ?? $data['background_used'] ?? null,
                    'mode' => $data['mode'] ?? null, 'photo_analysis' => $data['photo_analysis'] ?? null,
                    'processing_token' => null, 'processing_expires_at' => null, 'error_message' => null,
                ])->save();
                return true;
            });
            if ($completed) Storage::disk('local')->delete($photo->source_path);
        } catch (\Throwable $exception) {
            $this->fail($id, 'Pemrosesan foto gagal. Kredit yang dipesan telah dikembalikan.', $photo->processing_token);
            Log::warning('AI photo processing failed', ['photo_id' => $id, 'exception' => get_class($exception)]);
        }
    }

    public function fail(int $id, string $message, ?string $token = null): void
    {
        DB::transaction(function () use ($id, $message, $token) {
            $photo = AiPhoto::whereKey($id)->lockForUpdate()->first();
            if (!$photo || !in_array($photo->status, ['queued', 'processing'], true)) return;
            if ($token && $photo->processing_token !== $token) return;
            if ($photo->credit_state === 'reserved') {
                User::whereKey($photo->user_id)->increment('photo_credits');
                $photo->credit_state = 'refunded';
            }
            $photo->forceFill(['status' => 'failed', 'error_message' => $message, 'processing_token' => null, 'processing_expires_at' => null])->save();
        });
    }

    public function expire(int $id): void
    {
        DB::transaction(function () use ($id) {
            $photo = AiPhoto::whereKey($id)->lockForUpdate()->first();
            if (!$photo) return;
            $expired = ($photo->status === 'queued' && $photo->created_at->lte(now()->subMinutes(30)))
                || ($photo->status === 'processing' && $photo->processing_expires_at?->lte(now()));
            if ($expired) $this->fail($id, 'Pekerjaan melewati batas waktu. Kredit yang dipesan telah dikembalikan.', $photo->processing_token);
        });
    }
}
