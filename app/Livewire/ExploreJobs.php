<?php

namespace App\Livewire;

use App\Models\JobSource;
use App\Models\Vacancy;
use App\Models\VacancyListing;
use App\Models\JobBookmark;
use App\Models\SavedJobSearch;
use App\Models\ExplorePreference;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class ExploreJobs extends Component
{
    use WithPagination;

    private const FILTERS = ['search', 'sortBy', 'selectedPlatform', 'selectedField', 'selectedMajor', 'selectedWorkType', 'selectedProvince', 'selectedLocation', 'collection'];
    private ?bool $workspaceAvailable = null;
    public string $collection = 'all';
    public string $searchName = '';
    public string $appliedOn = '';
    public string $applicationLocation = '';
    #[\Livewire\Attributes\Locked]
    public string $feedback = '';
    public $search = '';
    public $sortBy = 'newest';
    #[\Livewire\Attributes\Locked]
    public ?int $previewJobId = null;
    public $selectedPlatform = '';
    public $selectedField = '';
    public $selectedMajor = '';
    public $selectedWorkType = '';
    public $selectedProvince = '';
    public $selectedLocation = '';
    
    // Modal Confirmation properties for saving jobs
    public $confirmingTrackJobId = null;
    public $confirmingJobTitle = '';
    public $confirmingJobCompany = '';
    public $confirmingJobPortal = '';
    public $confirmingJobUrl = '';

    // Modal Confirmation properties for reporting expired jobs
    public $confirmingReportJobId = null;
    public $confirmingReportJobTitle = '';
    public $confirmingReportJobCompany = '';

    protected $queryString = [
        'collection' => ['except' => 'all'],
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
        'selectedPlatform' => ['except' => ''],
        'selectedField' => ['except' => ''],
        'selectedMajor' => ['except' => ''],
        'selectedWorkType' => ['except' => ''],
        'selectedProvince' => ['except' => ''],
        'selectedLocation' => ['except' => ''],
    ];

    /** The job shown in the hero card: picked once per page load, so it changes on refresh but not while filtering. */
    #[\Livewire\Attributes\Locked]
    public ?int $heroJobId = null;

    public function mount()
    {
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->role === 'admin')) {
            return redirect()->route('admin.index');
        }
        $this->appliedOn = now('Asia/Jakarta')->toDateString();
        // Prefer recent jobs with a company logo so the card looks complete.
        $recent = Vacancy::visible()->orderByRaw('COALESCE(date_posted, first_seen_at) desc')->limit(40);
        $this->heroJobId = (clone $recent)->whereHas('company', fn ($q) => $q->whereNotNull('logo_url'))->pluck('id')->shuffle()->first()
            ?? $recent->pluck('id')->shuffle()->first();
        $hasQuery = collect(self::FILTERS)->contains(fn ($key) => request()->query->has($key));
        if (!$hasQuery && $this->workspaceReady()) {
            $this->fill($this->normalizeFilters(ExplorePreference::where('user_id', auth()->id())->value('filters') ?? []));
        }
    }

    public function workspaceReady(): bool
    {
        return $this->workspaceAvailable ??= Schema::hasTable('job_bookmarks') && Schema::hasTable('saved_job_searches') && Schema::hasTable('explore_preferences');
    }

    private function normalizeFilters(array|string $filters): array
    {
        if (is_string($filters)) $filters = json_decode($filters, true) ?: [];
        $result = [];
        foreach (self::FILTERS as $key) {
            $value = $filters[$key] ?? '';
            $result[$key] = is_string($value) ? mb_substr(trim($value), 0, 200) : '';
        }
        $result['sortBy'] = in_array($result['sortBy'], ['oldest', 'match'], true) ? $result['sortBy'] : 'newest';
        $result['collection'] = in_array($result['collection'], ['all', 'saved', 'applied'], true) ? $result['collection'] : 'all';
        if (!in_array($result['selectedWorkType'], ['', 'Onsite', 'Remote', 'Hybrid'], true)) $result['selectedWorkType'] = '';
        return $result;
    }

    private function currentFilters(): array
    {
        return $this->normalizeFilters($this->only(self::FILTERS));
    }

    private function rememberFilters(): void
    {
        if ($this->workspaceReady()) {
            ExplorePreference::updateOrCreate(['user_id' => auth()->id()], ['filters' => $this->currentFilters()]);
        }
    }

    public function dismissFeedback(): void { $this->feedback = ''; }

    public function updated($name): void
    {
        if (in_array($name, self::FILTERS, true)) {
            $this->fill($this->currentFilters());
            $this->resetPage();
            $this->rememberFilters();
        }
    }

    public function setCollection(string $collection): void
    {
        abort_unless(in_array($collection, ['all', 'saved', 'applied'], true), 422);
        $this->collection = $collection;
        $this->resetPage();
        $this->rememberFilters();
    }

    public function toggleBookmark(int $id): void
    {
        abort_unless(auth()->check(), 401);
        if (!$this->workspaceReady()) { $this->feedback = __('Saved jobs will be available after the workspace update.'); return; }
        DB::transaction(function () use ($id) {
            User::whereKey(auth()->id())->lockForUpdate()->firstOrFail();
            $bookmark = JobBookmark::where('user_id', auth()->id())->where('vacancy_id', $id)->first();
            if ($bookmark) {
                $bookmark->delete();
                $this->feedback = __('Removed from saved jobs. Your applications are unchanged.');
                return;
            }
            Vacancy::visible()->findOrFail($id);
            JobBookmark::create(['user_id' => auth()->id(), 'vacancy_id' => $id]);
            $this->feedback = __('Saved for later. No application has been recorded.');
        });
        if ($this->collection === 'saved') $this->resetPage();
    }

    public function saveSearch(): void
    {
        abort_unless(auth()->check(), 401);
        if (!$this->workspaceReady()) { $this->feedback = __('Saved searches will be available after the workspace update.'); return; }
        $this->resetValidation('searchName');
        $this->searchName = trim($this->searchName);
        $this->validate(['searchName' => 'required|string|max:60']);
        $filters = $this->currentFilters();
        $fingerprint = hash('sha256', json_encode($filters));
        DB::transaction(function () use ($filters, $fingerprint) {
            User::whereKey(auth()->id())->lockForUpdate()->firstOrFail();
            $searches = SavedJobSearch::where('user_id', auth()->id());
            if (!(clone $searches)->where('fingerprint', $fingerprint)->exists() && (clone $searches)->count() >= 20) {
                $this->addError('searchName', __('You can save up to 20 searches. Remove one to make room.'));
                return;
            }
            SavedJobSearch::updateOrCreate(['user_id' => auth()->id(), 'fingerprint' => $fingerprint], ['name' => $this->searchName, 'filters' => $filters]);
            $this->feedback = __('Search saved. Your filters are ready for next time.');
            $this->searchName = '';
        });
        $this->rememberFilters();
    }

    public function applySavedSearch(int $id): void
    {
        abort_unless($this->workspaceReady(), 503);
        $search = SavedJobSearch::where('user_id', auth()->id())->findOrFail($id);
        $this->fill($this->normalizeFilters($search->filters));
        $this->resetPage();
        $this->rememberFilters();
        $this->dispatch('explore-filters-restored');
        $this->feedback = __('Saved search applied.');
    }

    public function deleteSavedSearch(int $id): void
    {
        abort_unless($this->workspaceReady(), 503);
        SavedJobSearch::where('user_id', auth()->id())->findOrFail($id)->delete();
        $this->feedback = __('Saved search removed.');
    }

    /** Turn new-job alerts for a saved search on or off (Fase 6). Turning on starts from now, so old jobs are not re-sent. */
    public function toggleSearchAlert(int $id): void
    {
        abort_unless($this->workspaceReady(), 503);
        $search = SavedJobSearch::where('user_id', auth()->id())->findOrFail($id);
        if ($search->alertsOn()) {
            $search->update(['alert_channel' => 'none']);
            $this->feedback = __('Alerts turned off for :name.', ['name' => $search->name]);

            return;
        }
        if (! \App\JobEngine\Personalization\VacancyFilters::narrows((array) $search->filters)) {
            $this->feedback = __('Add a keyword or filter to this search before turning on alerts.');

            return;
        }
        $search->update(['alert_channel' => 'email', 'last_alerted_at' => now()]);
        $this->feedback = __('Alerts on. We will tell you about new jobs for :name once a day.', ['name' => $search->name]);
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function previewJob($id)
    {
        $this->previewJobId = Vacancy::visible()->findOrFail($id)->id;
    }

    public function closePreview()
    {
        $this->previewJobId = null;
    }

    public function clearFilter(string $name)
    {
        if (!in_array($name, ['search', 'selectedPlatform', 'selectedField', 'selectedMajor', 'selectedWorkType', 'selectedProvince', 'selectedLocation'], true)) return;
        $this->$name = '';
        if ($name === 'selectedProvince') $this->selectedLocation = '';
        if ($name === 'selectedField') $this->selectedMajor = '';
        $this->resetPage();
        $this->rememberFilters();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedPlatform()
    {
        $this->resetPage();
    }

    public function updatingSelectedField()
    {
        $this->resetPage();
        $this->selectedMajor = ''; // Reset major choice when sector changes
    }

    public function updatingSelectedMajor()
    {
        $this->resetPage();
    }

    public function updatingSelectedWorkType()
    {
        $this->resetPage();
    }

    public function updatingSelectedLocation()
    {
        $this->resetPage();
    }

    public function updatingSelectedProvince()
    {
        $this->resetPage();
        $this->selectedLocation = '';
    }

    public function selectLocation(string $province, string $location = '')
    {
        $this->selectedProvince = $province;
        $this->selectedLocation = $location;
        $this->resetPage();
        $this->rememberFilters();
    }

    public function resetLocationFilter()
    {
        $this->selectedProvince = '';
        $this->selectedLocation = '';
        $this->resetPage();
        $this->rememberFilters();
    }

    public function resetFilters()
    {
        $this->sortBy = 'newest';
        $this->search = '';
        $this->selectedPlatform = '';
        $this->selectedField = '';
        $this->selectedMajor = '';
        $this->selectedWorkType = '';
        $this->selectedProvince = '';
        $this->selectedLocation = '';
        $this->resetPage();
        $this->rememberFilters();
    }

    public function getMajorsProperty()
    {
        if (!empty($this->selectedField)) {
            return \App\Helpers\CategoryHelper::getMajorsForSektor($this->selectedField);
        }
        return \App\Helpers\CategoryHelper::getAllMajors();
    }

    public function initiateReportExpired($id)
    {
        $vacancy = Vacancy::visible()->with('company')->findOrFail($id);
        $this->confirmingReportJobId = $vacancy->id;
        $this->confirmingReportJobTitle = $vacancy->title;
        $this->confirmingReportJobCompany = (string) $vacancy->company_name;
    }

    public function cancelReportExpired()
    {
        $this->confirmingReportJobId = null;
    }

    public function confirmReportExpired()
    {
        if ($this->confirmingReportJobId) {
            $this->reportExpired($this->confirmingReportJobId);
            $this->confirmingReportJobId = null;
        }
    }

    public function reportExpired($id)
    {
        abort_unless(auth()->check(), 401);
        $accepted = $this->recordReport((int) $id);
        $this->feedback = $accepted
            ? __('Report submitted. The listing will be checked.')
            : __('Your report is already recorded, or the listing is no longer active.');
        session()->flash('report_success_' . $id, $this->feedback);
    }

    public function initiateTrackJob($id)
    {
        $vacancy = $this->vacancyQuery()->visible()->findOrFail($id);
        $this->appliedOn = now('Asia/Jakarta')->toDateString();
        $this->applicationLocation = (string) ($vacancy->location ?? '');
        $this->resetValidation();
        $this->previewJobId = null;
        $this->confirmingTrackJobId = $vacancy->id;
        $this->confirmingJobTitle = $vacancy->title;
        $this->confirmingJobCompany = (string) $vacancy->company_name;
        $this->confirmingJobPortal = $vacancy->source_platform;
        $this->confirmingJobUrl = (string) $vacancy->source_url;
    }

    public function cancelTrackJob()
    {
        $this->confirmingTrackJobId = null;
    }

    public function confirmTrackJob()
    {
        if ($this->confirmingTrackJobId) {
            $this->trackJob($this->confirmingTrackJobId);
            $this->confirmingTrackJobId = null;
        }
    }

    public function trackJob($id)
    {
        abort_unless(auth()->check(), 401);
        $vacancy = $this->vacancyQuery()->visible()->findOrFail($id);

        $this->applicationLocation = trim($this->applicationLocation);
        $this->validate(['applicationLocation' => 'required|string|max:255', 'appliedOn' => 'required|date_format:Y-m-d|before_or_equal:'.now('Asia/Jakarta')->toDateString()]);
        DB::transaction(function () use ($vacancy) {
            User::whereKey(auth()->id())->lockForUpdate()->firstOrFail();
            $exists = JobApplication::where('user_id', auth()->id())
                ->where(fn ($q) => $q->where('vacancy_id', $vacancy->id)->orWhereIn('platform_link', $vacancy->listings->pluck('url')->push($vacancy->source_url)->filter()))
                ->exists();
            if ($exists) {
                session()->flash('track_info_'.$vacancy->id, __('Already in your tracker.'));
                $this->feedback = __('Already in your tracker.');
                return;
            }
            JobApplication::create([
                'user_id' => auth()->id(), 'vacancy_id' => $vacancy->id, 'company_name' => $vacancy->company_name,
                'position' => $vacancy->title, 'location' => $this->applicationLocation,
                'platform' => $vacancy->source_platform, 'platform_link' => (string) $vacancy->source_url,
                'application_status' => 'On Process', 'recruitment_stage' => 'Applied',
                'application_date' => $this->appliedOn,
                'notes' => __('Application recorded from Explore Jobs.'),
            ]);
            session()->flash('track_success_'.$vacancy->id, __('Added to your tracker.'));
            $this->feedback = __('Application recorded. Your saved jobs are kept separately.');
        });
    }

    /** Vacancies with everything the cards and dialogs read, in one query each. */
    private function vacancyQuery()
    {
        return Vacancy::query()->with(['company', 'region', 'primaryListing.source', 'listings.source']);
    }

    /**
     * A user report: one per user and vacancy. It does not close the vacancy; it moves its
     * listings to the front of the verifier queue.
     */
    private function recordReport(int $vacancyId): bool
    {
        $vacancy = Vacancy::visible()->find($vacancyId);
        if (! $vacancy) {
            return false;
        }
        $inserted = DB::table('vacancy_reports')->insertOrIgnore([
            'vacancy_id' => $vacancy->id, 'user_id' => auth()->id(), 'reason' => 'closed', 'created_at' => now(), 'updated_at' => now(),
        ]);
        if ($inserted) {
            VacancyListing::where('vacancy_id', $vacancy->id)->whereIn('status', ['active', 'suspect'])->update(['next_check_at' => now()]);
        }

        return (bool) $inserted;
    }

    public function render()
    {
        $maintenanceDefault = !app()->environment('testing');
        if (\App\Models\Setting::get('explore_maintenance', $maintenanceDefault)) {
            return view('explore.maintenance')->layout('layouts.app');
        }

        $this->fill($this->currentFilters());
        $ready = $this->workspaceReady();
        $userId = auth()->id();
        $appliedVacancyIds = JobApplication::where('user_id', $userId)->whereNotNull('vacancy_id')->pluck('vacancy_id');

        $query = $this->vacancyQuery();
        if ($this->collection === 'saved' && $ready) {
            // Saved jobs stay visible even after they close, so users can tidy them up.
            $query->whereIn('id', JobBookmark::where('user_id', $userId)->whereNotNull('vacancy_id')->select('vacancy_id'))->whereNull('merged_into_id');
        } else {
            $query->visible();
            if ($this->collection === 'saved') {
                $query->whereRaw('1 = 0');
            }
        }
        if ($this->collection === 'applied') {
            $query->whereIn('id', $appliedVacancyIds);
        }

        \App\JobEngine\Personalization\VacancyFilters::apply($query, $this->currentFilters());

        // Best match (Fase 6, premium): rank the filtered ids by CV score; everything else sorts by date.
        $canMatch = auth()->user()->canAccessFeature('job_match');
        $scorer = app(\App\JobEngine\Personalization\MatchScorer::class);
        $ranked = $canMatch && $this->sortBy === 'match' ? rescue(fn () => $scorer->rankedIds(auth()->user()), [], false) : [];
        if ($ranked) {
            $position = array_flip($ranked);
            $filteredIds = (clone $query)->pluck('id')->sortBy(fn ($id) => $position[$id] ?? PHP_INT_MAX)->values();
            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
            $pageIds = $filteredIds->forPage($page, 12)->all();
            $rows = $this->vacancyQuery()->whereIn('id', $pageIds)->get()->sortBy(fn ($v) => array_search($v->id, $pageIds, true))->values();
            $postings = new \Illuminate\Pagination\LengthAwarePaginator($rows, $filteredIds->count(), 12, $page, ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'pageName' => 'page']);
        } else {
            $direction = $this->sortBy === 'oldest' ? 'asc' : 'desc';
            $postings = $query->orderByRaw('COALESCE(date_posted, first_seen_at) '.$direction)->orderBy('id', $direction)->paginate(12);
        }

        $ids = $postings->pluck('id')->push($this->previewJobId)->filter()->all();
        // Tracked jobs: linked by vacancy_id, or saved earlier with one of the vacancy's listing URLs.
        $urlToVacancy = VacancyListing::whereIn('vacancy_id', $ids)->get(['vacancy_id', 'url', 'apply_url'])
            ->flatMap(fn ($l) => array_filter([$l->url => $l->vacancy_id, (string) $l->apply_url => $l->vacancy_id], fn ($v, $k) => $k !== '', ARRAY_FILTER_USE_BOTH));
        $applications = JobApplication::where('user_id', $userId)
            ->where(fn ($q) => $q->whereIn('vacancy_id', $ids)->orWhereIn('platform_link', $urlToVacancy->keys()))
            ->get(['id', 'vacancy_id', 'platform_link'])
            ->mapWithKeys(fn ($a) => [($a->vacancy_id ?? $urlToVacancy[$a->platform_link] ?? 0) => $a->id])->forget(0);
        $previewJob = $this->previewJobId ? $this->vacancyQuery()->visible()->find($this->previewJobId) : null;
        // Scores for the cards on this page and the open dialog; empty when the CV is too thin or AI is off.
        $matchScores = $canMatch ? rescue(fn () => $scorer->forVacancies(auth()->user(), collect($postings->items())->when($previewJob, fn ($c) => $c->push($previewJob))), [], false) : [];

        $fieldsList = \App\Helpers\CategoryHelper::getSektorList();
        sort($fieldsList);
        $majorsList = $this->majors;
        sort($majorsList);

        return view('livewire.explore-jobs', [
            'postings' => $postings,
            'heroJob' => $this->heroJobId ? $this->vacancyQuery()->visible()->find($this->heroJobId) : null,
            'canMatch' => $canMatch,
            'matchScores' => $matchScores,
            'matchNeedsCv' => $canMatch && \App\JobEngine\Personalization\Embeddings::profileText(auth()->user()) === '',
            'savedJobs' => $applications->all(),
            'bookmarkedIds' => $ready ? JobBookmark::where('user_id', $userId)->whereIn('vacancy_id', $ids)->pluck('vacancy_id')->all() : [],
            'bookmarkCount' => $ready ? JobBookmark::where('user_id', $userId)->whereNotNull('vacancy_id')->count() : 0,
            'savedSearches' => $ready ? SavedJobSearch::where('user_id', $userId)->latest('updated_at')->get() : collect(),
            'workspaceReady' => $ready,
            'previewApplicationId' => $this->previewJobId ? ($applications[$this->previewJobId] ?? null) : null,
            'reportedIds' => DB::table('vacancy_reports')->where('user_id', $userId)->whereIn('vacancy_id', $ids)->pluck('vacancy_id')->all(),
            'userSkills' => auth()->user()->skills()->pluck('skill_name')->map(fn ($skill) => mb_strtolower(trim($skill)))->all(),
            'previewJob' => $previewJob,
            // The view reads name + target_domain; target_domain carries the source slug used as the filter value.
            'platformsList' => JobSource::whereHas('listings', fn ($q) => $q->whereIn('status', ['active', 'suspect']))->orderBy('name')
                ->get(['name', 'slug'])->map(fn ($s) => (object) ['name' => $s->name, 'target_domain' => $s->slug]),
            'catalogCount' => Vacancy::visible()->count(),
            'latestCheck' => Vacancy::visible()->max('last_verified_at') ?? Vacancy::visible()->max('last_seen_open_at'),
            'fieldsList' => $fieldsList,
            'majorsList' => $majorsList,
            'provincesList' => \App\Helpers\LocationHelper::getAllProvinces(),
            'locationsList' => $this->selectedProvince !== ''
                ? \App\Models\Region::where('level', 'city')->whereHas('parent', fn ($q) => $q->where('name', $this->selectedProvince))->orderBy('name')->pluck('name')
                : \App\Models\Region::whereIn('id', Vacancy::visible()->whereNotNull('region_id')->select('region_id'))->where('level', 'city')->orderBy('name')->pluck('name'),
        ])->layout('layouts.app');
    }
}
