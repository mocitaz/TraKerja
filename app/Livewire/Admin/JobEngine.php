<?php

namespace App\Livewire\Admin;

use App\JobEngine\Ai\AiGateway;
use App\JobEngine\Ingest\DiscoveryRunner;
use App\JobEngine\Intelligence\VacancyMerger;
use App\Jobs\DiscoverSourceJob;
use App\Models\CrawlRun;
use App\Models\JobSource;
use App\Models\Vacancy;
use App\Models\VacancyListing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Admin console for the job-data engine (TraKerja 2.0, Fase 4): source health and schedule,
 * the manual dedup review queue, and the weekly QA sample.
 */
class JobEngine extends Component
{
    public const QA_VERDICTS = [
        'correct' => 'Benar',
        'wrong_status' => 'Status salah (sudah tutup / masih buka)',
        'wrong_data' => 'Data salah (judul, lokasi, gaji, dll.)',
        'duplicate' => 'Duplikat yang lolos',
    ];

    #[Url]
    public string $tab = 'sources';

    public array $qaNotes = [];

    public string $flash = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
    }

    public function runNow(int $sourceId): void
    {
        $source = JobSource::findOrFail($sourceId);
        DiscoverSourceJob::dispatch($source->id, 300);
        $this->flash = "{$source->name} dikirim ke antrean ingest. Jalankan worker: php artisan queue:work --queue=ingest";
    }

    public function toggle(int $sourceId): void
    {
        $source = JobSource::findOrFail($sourceId);
        $source->update(['is_active' => ! $source->is_active]);
        $this->flash = $source->name.($source->is_active ? ' diaktifkan.' : ' dinonaktifkan.');
    }

    public function resolvePair(int $reviewId, bool $same): void
    {
        $review = DB::table('dedup_reviews')->where('id', $reviewId)->where('status', 'pending')->first();
        if (! $review) {
            return;
        }
        $listing = VacancyListing::find($review->vacancy_listing_id);
        $candidate = Vacancy::find($review->candidate_vacancy_id);

        if ($same && $listing?->vacancy && $candidate && ! $listing->vacancy->is($candidate)) {
            app(VacancyMerger::class)->merge($listing->vacancy, $candidate, 'manual', (float) $review->score);
        }
        DB::table('dedup_reviews')->where('id', $reviewId)->update([
            'status' => $same ? 'merged_manual' : 'separate', 'reviewed_by' => Auth::id(), 'reviewed_at' => now(), 'updated_at' => now(),
        ]);
        $this->flash = $same ? 'Digabung.' : 'Ditandai sebagai lowongan berbeda.';
    }

    /** 20 random active vacancies plus 5 recently closed ones for this ISO week. */
    public function drawQaSample(): void
    {
        $sample = now()->format('o-\WW');
        $ids = Vacancy::where('status', 'active')->inRandomOrder()->limit(20)->pluck('id')
            ->merge(Vacancy::where('status', 'closed')->where('closed_at', '>=', now()->subWeek())->inRandomOrder()->limit(5)->pluck('id'));
        foreach ($ids as $id) {
            DB::table('vacancy_qa_checks')->insertOrIgnore(['vacancy_id' => $id, 'sample' => $sample, 'created_at' => now(), 'updated_at' => now()]);
        }
        $this->flash = "Sampel {$sample}: {$ids->count()} loker.";
    }

    public function judge(int $checkId, string $verdict): void
    {
        abort_unless(array_key_exists($verdict, self::QA_VERDICTS), 422);
        DB::table('vacancy_qa_checks')->where('id', $checkId)->update([
            'verdict' => $verdict, 'note' => trim($this->qaNotes[$checkId] ?? '') ?: null,
            'checked_by' => Auth::id(), 'checked_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function render()
    {
        $sources = JobSource::query()->whereIn('slug', array_keys(DiscoveryRunner::ADAPTERS))->orderByDesc('is_active')->orderBy('name')->get();
        $lastRuns = CrawlRun::query()->where('run_type', 'discover')
            ->whereIn('id', CrawlRun::query()->where('run_type', 'discover')->selectRaw('max(id)')->groupBy('job_source_id'))
            ->get()->keyBy('job_source_id');
        $activeCounts = VacancyListing::query()->whereIn('status', ['active', 'suspect'])->selectRaw('job_source_id, count(*) as n')->groupBy('job_source_id')->pluck('n', 'job_source_id');

        $pairs = DB::table('dedup_reviews')->where('status', 'pending')->orderByDesc('score')->limit(50)->get()->map(function ($review) {
            $listing = VacancyListing::with(['source', 'vacancy.company', 'vacancy.region'])->find($review->vacancy_listing_id);
            $candidate = Vacancy::with(['company', 'region', 'listings.source'])->find($review->candidate_vacancy_id);

            return $listing && $candidate && $listing->vacancy && ! $listing->vacancy->is($candidate) && $candidate->status !== 'archived'
                ? ['review' => $review, 'listing' => $listing, 'candidate' => $candidate, 'detail' => json_decode((string) $review->score_detail, true)]
                : null;
        })->filter()->values();

        $sample = now()->format('o-\WW');
        $qa = DB::table('vacancy_qa_checks')->where('sample', $sample)->orderBy('id')->get();
        $qaVacancies = Vacancy::with(['company', 'region', 'listings.source'])->whereIn('id', $qa->pluck('vacancy_id'))->get()->keyBy('id');

        $verifyRun = CrawlRun::where('run_type', 'verify')->latest('id')->first();

        return view('livewire.admin.job-engine', [
            'sources' => $sources,
            'lastRuns' => $lastRuns,
            'activeCounts' => $activeCounts,
            'pairs' => $pairs,
            'qa' => $qa,
            'qaVacancies' => $qaVacancies,
            'sample' => $sample,
            'verdicts' => self::QA_VERDICTS,
            'verifyRun' => $verifyRun,
            'stats' => [
                'active' => Vacancy::where('status', 'active')->count(),
                'suspect' => Vacancy::where('status', 'suspect')->count(),
                'closed_24h' => Vacancy::where('status', 'closed')->where('closed_at', '>=', now()->subDay())->count(),
                'scam' => Vacancy::where('scam_flag', 'suspected')->whereIn('status', ['active', 'suspect'])->count(),
                'pending_pairs' => DB::table('dedup_reviews')->where('status', 'pending')->count(),
                'ai_today' => app(AiGateway::class)->spentToday(),
                'ai_budget' => (float) config('services.openai.jobs.daily_budget_usd'),
                'schedule' => (bool) config('job_sources.schedule_enabled'),
            ],
        ]);
    }
}
