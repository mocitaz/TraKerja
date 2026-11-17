<?php

namespace App\Livewire\Admin;

use App\JobEngine\Ai\AiGateway;
use App\JobEngine\Ingest\DiscoveryRunner;
use App\Jobs\DiscoverSourceJob;
use App\Models\CrawlRun;
use App\Models\JobSource;
use App\Models\Vacancy;
use App\Models\VacancyListing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admin console for the job-data engine (TraKerja 2.0, Fase 4): source health and schedule,
 * the manual dedup review queue, and the weekly QA sample.
 */
class JobEngine extends Component
{
    use WithPagination;

    public const TABS = ['overview', 'sources', 'runs', 'feed'];
    #[Url]
    public string $tab = 'overview';

    // Run log filters
    #[Url(as: 'run_source')]
    public string $runSource = '';

    #[Url(as: 'run_status')]
    public string $runStatus = '';

    public string $flash = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
    }

    public function selectTab(string $tab): void
    {
        abort_unless(in_array($tab, self::TABS, true), 422);
        $this->tab = $tab;
        $this->resetPage();
        $this->flash = '';
    }

    /** Quick actions on the overview: run the verifier, retry failed engine jobs, enrich what is still raw. */
    public function verifyNow(): void
    {
        \Illuminate\Support\Facades\Artisan::queue('jobs:verify')->onQueue('ingest');
        $this->flash = 'Verifikasi dikirim ke antrean ingest.';
    }

    public function retryFailed(): void
    {
        $ids = DB::table('failed_jobs')->whereIn('queue', ['ingest', 'ai', 'default'])->pluck('uuid');
        if ($ids->isNotEmpty()) {
            \Illuminate\Support\Facades\Artisan::call('queue:retry', ['id' => $ids->all()]);
        }
        $this->flash = $ids->count().' job gagal dikirim ulang ke antrean.';
    }

    public function enrichPending(): void
    {
        $n = Vacancy::visible()->whereNull('enriched_at')->limit(500)->pluck('id')->each(fn ($id) => \App\Jobs\EnrichVacancyJob::dispatch($id))->count();
        $this->flash = "{$n} loker dikirim ke antrean AI untuk dirapikan.";
    }

    public function updating($name): void
    {
        if (in_array($name, ['runSource', 'runStatus'], true)) {
            $this->resetPage();
        }
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

    public string $feedName = '';

    /** Shown once after a key is issued. */
    public string $issuedKey = '';

    public function issueFeedKey(): void
    {
        $this->validate(['feedName' => 'required|string|max:80']);
        [$client, $key] = \App\Models\FeedClient::issue($this->feedName);
        $this->issuedKey = $key;
        $this->feedName = '';
        $this->flash = "Kunci untuk \"{$client->name}\" dibuat. Salin sekarang; kunci tidak ditampilkan lagi.";
    }

    public function revokeFeedClient(int $id): void
    {
        \App\Models\FeedClient::whereKey($id)->update(['is_active' => false]);
        $this->issuedKey = '';
        $this->flash = 'Kunci dinonaktifkan.';
    }

    public function render()
    {
        $sources = JobSource::query()->whereIn('slug', array_keys(DiscoveryRunner::ADAPTERS))->orderByDesc('is_active')->orderBy('name')->get();
        $lastRuns = CrawlRun::query()->where('run_type', 'discover')
            ->whereIn('id', CrawlRun::query()->where('run_type', 'discover')->selectRaw('max(id)')->groupBy('job_source_id'))
            ->get()->keyBy('job_source_id');
        $activeCounts = VacancyListing::query()->whereIn('status', ['active', 'suspect'])->selectRaw('job_source_id, count(*) as n')->groupBy('job_source_id')->pluck('n', 'job_source_id');

        $verifyRun = CrawlRun::where('run_type', 'verify')->latest('id')->first();

        $runs = $this->tab === 'runs'
            ? CrawlRun::with('source')->when($this->runSource !== '', fn ($q) => $q->where('job_source_id', $this->runSource))
                ->when($this->runStatus !== '', fn ($q) => $q->where('status', $this->runStatus))->latest('id')->paginate(30)
            : null;

        return view('livewire.admin.job-engine', [
            'runs' => $runs,
            'overview' => $this->tab === 'overview' ? $this->overview($sources) : null,
            'sources' => $sources,
            'lastRuns' => $lastRuns,
            'activeCounts' => $activeCounts,
            'verifyRun' => $verifyRun,
            'feedClients' => $this->tab === 'feed' ? \App\Models\FeedClient::orderByDesc('is_active')->orderBy('name')->get() : collect(),
            'stats' => [
                'active' => Vacancy::where('status', 'active')->count(),
                'suspect' => Vacancy::where('status', 'suspect')->count(),
                'closed_24h' => Vacancy::where('status', 'closed')->where('closed_at', '>=', now()->subDay())->count(),
                'new_24h' => Vacancy::visible()->where('first_seen_at', '>=', now()->subDay())->count(),
                'archived' => Vacancy::where('status', 'archived')->count(),
                'scam' => Vacancy::where('scam_flag', 'suspected')->whereIn('status', ['active', 'suspect'])->count(),
                'pending_pairs' => DB::table('dedup_reviews')->where('status', 'pending')->count(),
                'ai_today' => app(AiGateway::class)->spentToday(),
                'ai_budget' => (float) config('services.openai.jobs.daily_budget_usd'),
                'schedule' => (bool) config('job_sources.schedule_enabled'),
            ],
        ]);
    }

    /** Everything the overview tab shows: alerts, recent runs, queues, AI spend and data coverage. */
    private function overview($sources): array
    {
        $alerts = [];
        if (! config('job_sources.schedule_enabled')) {
            $alerts[] = ['info', 'Jadwal otomatis nonaktif', 'Discovery, verifikasi, enrichment, embedding dan alert hanya jalan manual. Set JOB_ENGINE_SCHEDULE_ENABLED=true di server.'];
        }
        $lastSuccess = CrawlRun::where('run_type', 'discover')->where('status', 'success')->selectRaw('job_source_id, max(finished_at) as at')->groupBy('job_source_id')->pluck('at', 'job_source_id');
        foreach ($sources->where('is_active', true) as $src) {
            if (in_array($src->health, ['failing', 'degraded'], true)) {
                $alerts[] = [$src->health === 'failing' ? 'bad' : 'warn', "{$src->name}: ".($src->health === 'failing' ? 'gagal' : 'menurun'), $src->health_reason ?: 'Lihat riwayat run untuk detail galat.'];
            }
            $at = $lastSuccess[$src->id] ?? null;
            if (! $at || \Illuminate\Support\Carbon::parse($at)->lt(now()->subDay())) {
                $alerts[] = ['warn', "{$src->name}: tidak ada run sukses 24 jam terakhir", $at ? 'Terakhir sukses '.\Illuminate\Support\Carbon::parse($at)->locale('id')->diffForHumans().'.' : 'Belum pernah sukses.'];
            }
        }
        foreach (app(\App\JobEngine\Ingest\EngineMonitor::class)->anomalies() as $a) {
            $alerts[] = ['warn', "{$a['source']}: loker baru turun drastis", "{$a['today']} loker baru 24 jam terakhir, biasanya ~{$a['average']}/hari. Kemungkinan tata letak situs berubah; cek riwayat run."];
        }
        $verify = CrawlRun::where('run_type', 'verify')->latest('id')->first();
        if (! $verify || $verify->started_at->lt(now()->subHours(6))) {
            $alerts[] = ['warn', 'Verifikasi loker belum jalan 6 jam terakhir', 'Loker yang sudah tutup bisa masih tampil buka. Jalankan jobs:verify atau nyalakan jadwal.'];
        }
        $spent = app(AiGateway::class)->spentToday();
        $budget = (float) config('services.openai.jobs.daily_budget_usd');
        if ($budget > 0 && $spent >= .8 * $budget) {
            $alerts[] = ['warn', 'Biaya AI mendekati batas harian', '$'.number_format($spent, 3).' dari $'.number_format($budget, 2).'. Enrichment berhenti otomatis saat batas tercapai.'];
        }
        $failedJobs = DB::table('failed_jobs')->where('failed_at', '>=', now()->subDay())->count();
        if ($failedJobs) {
            $alerts[] = ['bad', "{$failedJobs} job antrean gagal dalam 24 jam", 'Periksa dengan php artisan queue:failed, lalu queue:retry setelah penyebabnya diperbaiki.'];
        }
        $pending = DB::table('dedup_reviews')->where('status', 'pending')->count();
        if ($pending) {
            $alerts[] = ['info', "{$pending} pasangan duplikat menunggu keputusan", 'Buka tab Duplikat untuk menggabung atau memisahkan.'];
        }
        $reports = DB::table('vacancy_reports')->whereNull('resolved_at')->where('created_at', '>=', now()->subWeek())->distinct('vacancy_id')->count('vacancy_id');
        if ($reports) {
            $alerts[] = ['info', "{$reports} loker dilaporkan pengguna minggu ini", 'Verifier sudah memajukan ceknya. Filter Loker → Dilaporkan untuk melihat.'];
        }

        $visible = Vacancy::visible();
        $total = max(1, (clone $visible)->count());
        $coverage = [
            'Wilayah dikenali' => (clone $visible)->whereNotNull('region_id')->count() / $total,
            'Dirapikan AI' => (clone $visible)->whereNotNull('enriched_at')->count() / $total,
            'Siap skor cocok CV' => DB::table('vacancy_embeddings')->whereIn('vacancy_id', (clone $visible)->select('id'))->count() / $total,
            'Ada gaji' => (clone $visible)->whereNotNull('salary_min')->count() / $total,
        ];

        $days = collect(range(6, 0))->map(fn ($d) => now()->subDays($d)->toDateString());
        $spend = DB::table('ai_calls')->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('date(created_at) as d, sum(cost_usd) as usd, count(*) as calls')->groupBy('d')->get()->keyBy('d');

        return [
            'alerts' => $alerts,
            'recentRuns' => CrawlRun::with('source')->latest('id')->limit(8)->get(),
            'queues' => collect(['ingest', 'ai', 'default'])->mapWithKeys(fn ($q) => [$q => DB::table('jobs')->where('queue', $q)->count()]),
            'failedJobs' => $failedJobs,
            'coverage' => $coverage,
            'spend' => $days->map(fn ($d) => ['day' => $d, 'usd' => (float) ($spend[$d]->usd ?? 0), 'calls' => (int) ($spend[$d]->calls ?? 0)]),
            'budget' => $budget,
        ];
    }
}
