<?php

namespace App\Services;

use App\Jobs\ProcessAiStudio;
use App\Models\{AiStudioRun, AiAnalyzerResult, CoverLetter, OutreachDraft, Setting, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log, Storage};
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class AiStudioProcessingService
{
    public function submit(Request $request, string $task): AiStudioRun
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('ai_studio_runs')) throw ValidationException::withMessages(['request' => 'AI Studio is being updated. Please try again shortly.']);
        $context = app(StudioContextService::class)->prepare($request, $task);
        $path = null;
        $created = false;
        try {
            $run = DB::transaction(function () use ($request, $task, $context, &$path, &$created) {
                $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
                $existing = AiStudioRun::where('user_id', $user->id)->where('request_key', $request->input('request_key'))->first();
                if ($existing) {
                    if ($existing->task !== $task) throw ValidationException::withMessages(['request_key' => 'Start a new request for a different task.']);
                    return $existing;
                }
                if (AiStudioRun::where('user_id', $user->id)->whereIn('status', ['queued', 'processing'])->count() >= 2) throw ValidationException::withMessages(['request' => 'Two requests are already processing. Wait for a result before starting another.']);
                if (AiStudioRun::where('user_id', $user->id)->where('created_at', '>=', now()->subDay())->count() >= 60) throw ValidationException::withMessages(['request' => 'Your daily Studio request limit has been reached. Please try again later.']);
                $field = $this->creditField($task);
                $metered = $field && Setting::isMonetizationEnabled();
                if ($metered && User::whereKey($user->id)->where($field, '>', 0)->decrement($field) !== 1) throw ValidationException::withMessages(['credits' => 'No credits available for this task. Manage credits in Profile.']);
                $sourceName = null;
                if ($context['file'] || $context['resume']) {
                    $path = 'ai-studio-sources/'.$user->id.'/'.Str::uuid().'.pdf';
                    $sourceName = $context['file']?->getClientOriginalName() ?? $context['resume']->file_name;
                    $stored = $context['file']
                        ? $context['file']->storeAs(dirname($path), basename($path), 'local')
                        : Storage::disk('local')->copy($context['resume']->file_path, $path);
                    if (!$stored) throw new \RuntimeException('Private source storage failed');
                }
                $created = true;
                return AiStudioRun::create([
                    'user_id' => $user->id, 'task' => $task, 'request_key' => $request->input('request_key'),
                    'title' => Str::limit($context['payload']['company_name'] ?: ($sourceName ?: 'CV review'), 250, ''),
                    'payload' => $context['payload'], 'source_path' => $path, 'source_name' => $sourceName,
                    'credit_state' => $metered ? 'reserved' : 'none', 'model' => app(GeminiStudioService::class)->model($task),
                ]);
            });
        } catch (\Throwable $exception) {
            if ($path) Storage::disk('local')->delete($path);
            throw $exception;
        }
        if ($created) {
            try { ProcessAiStudio::dispatch($run->id)->afterCommit(); }
            catch (\Throwable) { $this->fail($run->id, 'queue'); }
        }
        return $run->refresh();
    }

    public function process(int $id): void
    {
        // Lock the user before the run consistently with submission/failure to avoid deadlocks.
        $owner = AiStudioRun::whereKey($id)->value('user_id');
        if (!$owner) return;
        $run = DB::transaction(function () use ($id, $owner) {
            if (!User::whereKey($owner)->lockForUpdate()->first()) return null;
            $run = AiStudioRun::whereKey($id)->lockForUpdate()->first();
            if (!$run || $run->status !== 'queued') return null;
            $run->update(['status' => 'processing', 'processing_token' => (string) Str::uuid(), 'processing_expires_at' => now()->addSeconds(180)]);
            return $run;
        });
        if (!$run) return;
        try {
            $pdf = $run->source_path ? Storage::disk('local')->get($run->source_path) : null;
            if ($run->source_path && !$pdf) throw new StudioProviderException('source');
            $result = app(GeminiStudioService::class)->generate($run->task, $run->payload, $pdf, $run->model);
            DB::transaction(function () use ($run, $result) {
                $user = User::whereKey($run->user_id)->lockForUpdate()->first();
                $locked = AiStudioRun::whereKey($run->id)->lockForUpdate()->first();
                if (!$user || !$locked || $locked->status !== 'processing' || $locked->processing_token !== $run->processing_token) return;
                $payload = $locked->payload;
                $saved = match ($locked->task) {
                    'analyzer' => AiAnalyzerResult::create(['user_id' => $user->id, 'job_description' => $payload['job_description'], 'resume_file_name' => $locked->source_name, 'analysis_result' => $result]),
                    'cover-letter' => CoverLetter::create(['user_id' => $user->id, 'company_name' => $payload['company_name'], 'job_title' => $payload['job_title'], 'job_description' => $payload['job_description'], 'language' => $payload['language'], 'tone' => $payload['tone'], 'content' => $result['content']]),
                    'outreach' => OutreachDraft::create(['user_id' => $user->id, 'company_name' => $payload['company_name'], 'job_title' => $payload['job_title'], 'recruiter_name' => $payload['recruiter_name'] ?? null, 'channel' => $payload['channel'], 'tone' => $payload['tone'], 'content' => $result['content']]),
                };
                if ($locked->task === 'analyzer' && !$user->has_used_ai_analyzer_trial) $user->useAiAnalyzerTrial();
                $locked->update(['status' => 'completed', 'result_id' => $saved->id, 'credit_state' => $locked->credit_state === 'reserved' ? 'consumed' : 'none',
                    'processing_token' => null, 'processing_expires_at' => null, 'payload' => []]);
            });
            if ($run->fresh()?->status === 'completed') $this->cleanSource($run);
        } catch (\Throwable $exception) {
            $this->fail($id, $exception instanceof StudioProviderException ? $exception->reason : 'processing', $run->processing_token);
            Log::warning('AI Studio request failed', ['run_id' => $id, 'task' => $run->task, 'exception' => get_class($exception)]);
        }
    }

    public function fail(int $id, string $code, ?string $token = null): void
    {
        $owner = AiStudioRun::whereKey($id)->value('user_id');
        if (!$owner) return;
        $run = DB::transaction(function () use ($id, $owner, $code, $token) {
            User::whereKey($owner)->lockForUpdate()->first();
            $run = AiStudioRun::whereKey($id)->lockForUpdate()->first();
            if (!$run || !in_array($run->status, ['queued', 'processing'], true) || ($token && $run->processing_token !== $token)) return null;
            if ($run->credit_state === 'reserved') {
                User::whereKey($owner)->increment($this->creditField($run->task));
                $run->credit_state = 'refunded';
            }
            $message = match ($code) {
                'unreadable_pdf' => 'The PDF could not be read as a CV. Upload a readable PDF and try again.',
                'rate_limit' => 'The AI service is busy. Please try again later.',
                'configuration' => 'The AI service is unavailable. Please contact support if this continues.',
                'expired' => 'This request exceeded its processing time. Please try again.',
                default => 'This request could not be completed. Your saved inputs are available to retry.',
            };
            $run->fill(['status' => 'failed', 'error_code' => $code, 'error_message' => $message, 'processing_token' => null, 'processing_expires_at' => null])->save();
            return $run;
        });
    }

    public function retry(Request $request, AiStudioRun $source): AiStudioRun
    {
        abort_unless($source->user_id === $request->user()->id && !$request->user()->isAdmin(), 404);
        abort_unless($source->status === 'failed', 422);
        $request->validate(['request_key' => 'required|uuid']);
        $payload = $source->payload;
        if (!$payload) throw ValidationException::withMessages(['request' => 'The saved inputs expired. Start a new request.']);
        $retry = Request::create('/', 'POST', $payload + ['request_key' => $request->input('request_key')]);
        $retry->setUserResolver(fn () => $request->user());
        if ($source->source_path) {
            if (!Storage::disk('local')->exists($source->source_path)) throw ValidationException::withMessages(['resume' => 'The saved PDF expired. Upload it again.']);
            $retry->files->set('resume', new \Illuminate\Http\UploadedFile(Storage::disk('local')->path($source->source_path), $source->source_name, 'application/pdf', null, true));
        }
        return $this->submit($retry, $source->task);
    }

    public function recover(): void
    {
        AiStudioRun::whereIn('status', ['queued', 'processing'])->select('id')->chunkById(100, function ($runs) {
            foreach ($runs as $row) {
                $run = AiStudioRun::find($row->id);
                if (!$run) continue;
                if (($run->status === 'queued' && $run->created_at->lte(now()->subMinutes(30))) || ($run->status === 'processing' && $run->processing_expires_at?->lte(now()))) $this->expire($run->id);
                elseif ($run->status === 'queued') ProcessAiStudio::dispatch($run->id);
            }
        });
        AiStudioRun::where('updated_at', '<', now()->subDays(7))->whereIn('status', ['failed', 'completed'])->chunkById(100, function ($runs) {
            foreach ($runs as $run) {
                $this->cleanSource($run);
                $run->update(['payload' => []]);
            }
        });
    }

    private function expire(int $id): void
    {
        $run = AiStudioRun::find($id);
        if (!$run) return;
        // Token comparison prevents an expired worker from completing after a refund.
        $this->fail($id, 'expired', $run->processing_token);
    }

    private function cleanSource(AiStudioRun $run): void
    {
        if ($run->source_path) Storage::disk('local')->delete($run->source_path);
    }

    private function creditField(string $task): ?string
    {
        return match ($task) { 'analyzer' => 'ai_credits', 'cover-letter' => 'cl_credits', default => null };
    }
}
