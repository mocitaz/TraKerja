<?php
namespace App\Support;

use App\Models\{AiAnalyzerResult, AiPhoto, CoverLetter, OutreachDraft, AiStudioRun, Setting, User};
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class AiStudioWorkspace
{
    public const TASKS = ['analyzer', 'cover-letter', 'photo', 'outreach'];

    public function render(User $user, ?string $task = null, ?Model $selected = null): View
    {
        $task = in_array($task, self::TASKS, true) ? $task : 'analyzer';
        $models = ['analyzer' => AiAnalyzerResult::class, 'cover-letter' => CoverLetter::class, 'photo' => AiPhoto::class, 'outreach' => OutreachDraft::class];
        $studioReady = \Illuminate\Support\Facades\Schema::hasTable('ai_studio_runs');
        abort_if(!$studioReady && request()->filled('run'), 503, 'AI Studio is being updated. Please try again shortly.');
        $run = $studioReady && request()->filled('run') ? AiStudioRun::where('user_id', $user->id)->findOrFail(request()->integer('run')) : null;
        if ($run) {
            $task = $run->task;
            if ($run->status === 'completed') {
                $selected = $models[$task]::where('user_id', $user->id)->findOrFail($run->result_id);
                $run = null;
            }
        }
        if (!$selected && !$run && request()->filled('id')) {
            $selected = $models[$task]::where('user_id', $user->id)->findOrFail(request()->integer('id'));
        }
        if ($selected) abort_unless((int) $selected->user_id === (int) $user->id, 403);
        $union = null;
        foreach ($models as $type => $model) {
            $titleColumn = match ($type) { 'analyzer' => 'resume_file_name', 'photo' => 'original_photo_name', default => 'company_name' };
            $subtitleColumn = in_array($type, ['analyzer', 'photo'], true) ? 'NULL' : 'job_title';
            $query = $model::where('user_id', $user->id)->select('id', 'created_at')
                ->selectRaw('? as task', [$type])->selectRaw($titleColumn.' as title, '.$subtitleColumn.' as subtitle, 0 as is_run')->toBase();
            $union = $union ? $union->unionAll($query) : $query;
        }
        if ($studioReady) $union->unionAll(AiStudioRun::where('user_id', $user->id)->where('status', '!=', 'completed')->select('id', 'created_at', 'task', 'title')->selectRaw('status as subtitle, 1 as is_run')->toBase());
        $history = \Illuminate\Support\Facades\DB::query()->fromSub($union, 'studio_history')
            ->orderByDesc('created_at')->orderByDesc('id')->orderBy('task')->paginate(30, ['*'], 'history_page')->withQueryString();
        $history->through(fn ($record) => [
            'task' => $record->task, 'id' => (int) $record->id, 'is_run' => (bool) $record->is_run,
            'date' => \Illuminate\Support\Carbon::parse($record->created_at),
            // A pending or failed request shows its state as a badge, not in the title.
            'title' => $record->is_run ? (string) $record->title : $record->title.($record->subtitle ? ' · '.$record->subtitle : ''),
            'status' => $record->is_run ? (string) $record->subtitle : null,
            'url' => route('ai-studio.index', ['task' => $record->task, $record->is_run ? 'run' : 'id' => $record->id]),
        ]);
        // Versions of a cover letter or message: the first draft and its revisions.
        $versions = collect();
        if ($selected && in_array($task, ['cover-letter', 'outreach'], true) && \Illuminate\Support\Facades\Schema::hasColumn($selected->getTable(), 'root_id')) {
            $root = $selected->root_id ?: $selected->id;
            $versions = $models[$task]::where('user_id', $user->id)->where(fn ($q) => $q->whereKey($root)->orWhere('root_id', $root))->orderBy('id')->get(['id', 'revision_note', 'created_at']);
        }
        return view('ai-studio.index', [
            'prefill' => $this->prefill($user, (string) request()->query('from', '')),
            'versions' => $versions,
            'revisionsLeft' => max(0, \App\Http\Controllers\AiStudioController::FREE_REVISIONS - max(0, $versions->count() - 1)),
            'studioUser' => $user, 'task' => $task, 'selected' => $selected, 'run' => $run,
            'applications' => $user->jobApplications()->latest()->limit(100)->get(['id', 'company_name', 'position']),
            'savedResumes' => $user->resumes()->latest()->limit(100)->get()->filter(fn ($resume) => $resume->is_pdf)->values(),
            'history' => $history,
            'studioReady' => $studioReady,
            'generatedWithAi' => $studioReady && $selected && AiStudioRun::where('user_id', $user->id)->where('task', $task)->where('status', 'completed')->where('result_id', $selected->id)->exists(),
            'metered' => Setting::isMonetizationEnabled(),
            'balances' => ['analyzer' => $user->getRemainingAiAnalyzer(), 'cover-letter' => $user->getRemainingCoverLetter(), 'photo' => $user->getRemainingPhoto()],
            'access' => ['analyzer' => $studioReady && $user->canAccessAiAnalyzerWithLimit(), 'cover-letter' => $studioReady && $user->canAccessCoverLetterWithLimit(), 'photo' => $user->canAccessPhotoWithLimit(), 'outreach' => $studioReady],
        ]);
    }

    /**
     * "Write a cover letter from this review": the next tool starts with the company, role and job description of the
     * result it came from (from=analyzer:12, from=cover-letter:5). Only the member's own results are read.
     */
    private function prefill(User $user, string $from): array
    {
        if (! preg_match('/^(analyzer|cover-letter|outreach):(\d+)$/', $from, $m)) {
            return [];
        }
        $source = match ($m[1]) {
            'analyzer' => AiAnalyzerResult::where('user_id', $user->id)->find((int) $m[2]),
            'cover-letter' => CoverLetter::where('user_id', $user->id)->find((int) $m[2]),
            'outreach' => OutreachDraft::where('user_id', $user->id)->find((int) $m[2]),
        };
        if (! $source) {
            return [];
        }
        if ($m[1] === 'analyzer') {
            $role = (string) ($source->analysis_result['target_role'] ?? '');
            [$title, $company] = array_pad(array_map('trim', explode('·', $role, 2)), 2, '');

            return array_filter(['job_title' => $title, 'company_name' => $company, 'job_description' => (string) $source->job_description]);
        }

        return array_filter(['company_name' => (string) $source->company_name, 'job_title' => (string) $source->job_title, 'job_description' => (string) ($source->job_description ?? '')]);
    }
}
