<?php

namespace App\Livewire;

use App\Models\JobPosting;
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

    public function mount()
    {
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->role === 'admin')) {
            return redirect()->route('admin.index');
        }
        $this->appliedOn = now('Asia/Jakarta')->toDateString();
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
        $result['sortBy'] = $result['sortBy'] === 'oldest' ? 'oldest' : 'newest';
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
            $bookmark = JobBookmark::where('user_id', auth()->id())->where('job_posting_id', $id)->first();
            if ($bookmark) {
                $bookmark->delete();
                $this->feedback = __('Removed from saved jobs. Your applications are unchanged.');
                return;
            }
            JobPosting::where('status', 'active')->findOrFail($id);
            JobBookmark::create(['user_id' => auth()->id(), 'job_posting_id' => $id]);
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

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function previewJob($id)
    {
        $posting = JobPosting::where('status', 'active')->findOrFail($id);
        $this->previewJobId = $posting->id;
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
        $posting = JobPosting::with('scraperSource')->where('status', 'active')->findOrFail($id);
        if ($posting) {
            $this->confirmingReportJobId = $id;
            $this->confirmingReportJobTitle = $posting->title;
            $this->confirmingReportJobCompany = $posting->company_name;
        }
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
        $accepted = app(\App\Services\JobPostingReportService::class)->report(auth()->user(), (int) $id);
        $this->feedback = $accepted
            ? __('Report submitted. The listing will be checked.')
            : __('Your report is already recorded, or the listing is no longer active.');
        session()->flash('report_success_' . $id, $this->feedback);
    }

    public function initiateTrackJob($id)
    {
        $posting = JobPosting::with('scraperSource')->where('status', 'active')->findOrFail($id);
        if ($posting) {
            $this->appliedOn = now('Asia/Jakarta')->toDateString();
            $this->applicationLocation = $posting->location ?? '';
            $this->resetValidation();
            $this->previewJobId = null;
            $this->confirmingTrackJobId = $id;
            $this->confirmingJobTitle = $posting->title;
            $this->confirmingJobCompany = $posting->company_name;
            $this->confirmingJobPortal = $posting->source_platform;
            $this->confirmingJobUrl = $posting->raw_url;
        }
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
        $posting = JobPosting::with('scraperSource')->where('status', 'active')->findOrFail($id);
        
        $this->applicationLocation = trim($this->applicationLocation);
        $this->validate(['applicationLocation' => 'required|string|max:255', 'appliedOn' => 'required|date_format:Y-m-d|before_or_equal:'.now('Asia/Jakarta')->toDateString()]);
        DB::transaction(function () use ($posting) {
            User::whereKey(auth()->id())->lockForUpdate()->firstOrFail();
            $exists = JobApplication::where('user_id', auth()->id())->where('platform_link', $posting->raw_url)->exists();
            if ($exists) {
                session()->flash('track_info_'.$posting->id, __('Already in your tracker.'));
                $this->feedback = __('Already in your tracker.');
                return;
            }
            JobApplication::create([
                'user_id' => auth()->id(), 'company_name' => $posting->company_name,
                'position' => $posting->title, 'location' => $this->applicationLocation,
                'platform' => $posting->source_platform, 'platform_link' => $posting->raw_url,
                'application_status' => 'On Process', 'recruitment_stage' => 'Applied',
                'application_date' => $this->appliedOn,
                'notes' => __('Application recorded from Explore Jobs.'),
            ]);
            session()->flash('track_success_'.$posting->id, __('Added to your tracker.'));
            $this->feedback = __('Application recorded. Your saved jobs are kept separately.');
        });
    }

    public function render()
    {
        $maintenanceDefault = !app()->environment('testing');
        if (\App\Models\Setting::get('explore_maintenance', $maintenanceDefault)) {
            return view('explore.maintenance')->layout('layouts.app');
        }

        $this->fill($this->currentFilters());
        $ready = $this->workspaceReady();
        $query = JobPosting::with('scraperSource');
        if ($this->collection === 'saved' && $ready) {
            $query->whereIn('id', JobBookmark::where('user_id', auth()->id())->select('job_posting_id'));
        } else {
            $query->where('status', 'active');
            if ($this->collection === 'saved') $query->whereRaw('1 = 0');
        }
        if ($this->collection === 'applied') {
            $query->whereIn('raw_url', JobApplication::where('user_id', auth()->id())->whereNotNull('platform_link')->where('platform_link', '!=', '')->select('platform_link'));
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('company_name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->selectedPlatform)) {
            $query->whereHas('scraperSource', function($q) {
                $q->where('target_domain', 'like', '%' . $this->selectedPlatform . '%');
            });
        }

        if (!empty($this->selectedField)) {
            $query->where('category_field', $this->selectedField);
        }

        if (!empty($this->selectedMajor)) {
            $query->where('category_major', $this->selectedMajor);
        }

        if (!empty($this->selectedWorkType)) {
            $query->where('work_type', $this->selectedWorkType);
        }

        if (!empty($this->selectedProvince)) {
            if (!empty($this->selectedLocation)) {
                $query->where('location', $this->selectedLocation);
            } else {
                $provinceCities = \App\Helpers\LocationHelper::getCitiesForProvince($this->selectedProvince);
                $query->whereIn('location', $provinceCities);
            }
        } elseif (!empty($this->selectedLocation)) {
            $query->where('location', $this->selectedLocation);
        }

        $direction = $this->sortBy === 'oldest' ? 'asc' : 'desc';
        $postings = $query->orderBy('created_at', $direction)->orderBy('id', $direction)->paginate(12);
        $applications = \App\Models\JobApplication::where('user_id', auth()->id())
            ->whereIn('platform_link', $postings->pluck('raw_url'))->get(['id', 'platform_link']);
        $savedJobs = [];
        foreach ($postings as $posting) {
            $saved = $applications->first(fn ($application) => $application->platform_link === $posting->raw_url);
            if ($saved) $savedJobs[$posting->id] = $saved->id;
        }
        $reportedIds = \Illuminate\Support\Facades\DB::table('job_posting_reports')->where('user_id', auth()->id())
            ->whereIn('job_posting_id', $postings->pluck('id')->push($this->previewJobId)->filter())->pluck('job_posting_id')->all();

        $fieldsList = \App\Helpers\CategoryHelper::getSektorList();
        sort($fieldsList);

        $majorsList = $this->majors;
        sort($majorsList);

        return view('livewire.explore-jobs', [
            'postings' => $postings,
            'savedJobs' => $savedJobs,
            'bookmarkedIds' => $ready ? JobBookmark::where('user_id', auth()->id())->whereIn('job_posting_id', $postings->pluck('id')->push($this->previewJobId)->filter())->pluck('job_posting_id')->all() : [],
            'bookmarkCount' => $ready ? JobBookmark::where('user_id', auth()->id())->count() : 0,
            'savedSearches' => $ready ? SavedJobSearch::where('user_id', auth()->id())->latest('updated_at')->get() : collect(),
            'workspaceReady' => $ready,
            'previewApplicationId' => $this->previewJobId ? JobApplication::where('user_id', auth()->id())->where('platform_link', JobPosting::whereKey($this->previewJobId)->value('raw_url'))->value('id') : null,
            'reportedIds' => $reportedIds,
            'userSkills' => auth()->user()->skills()->pluck('skill_name')->map(fn ($skill) => mb_strtolower(trim($skill)))->all(),
            'previewJob' => $this->previewJobId ? JobPosting::with('scraperSource')->where('status', 'active')->find($this->previewJobId) : null,
            'platformsList' => \App\Models\ScraperSource::whereHas('jobPostings', fn ($sourceQuery) => $sourceQuery->where('status', 'active'))->orderBy('name')->get(['name', 'target_domain']),
            'catalogCount' => JobPosting::where('status', 'active')->count(),
            'latestCheck' => JobPosting::where('status', 'active')->max('last_validated_at'),
            'fieldsList' => $fieldsList,
            'majorsList' => $majorsList,
            'provincesList' => \App\Helpers\LocationHelper::getAllProvinces(),
            'locationsList' => !empty($this->selectedProvince) 
                ? \App\Helpers\LocationHelper::getCitiesForProvince($this->selectedProvince)
                : JobPosting::where('status', 'active')->whereNotNull('location')->where('location', '!=', '')->distinct()->orderBy('location')->pluck('location'),
        ])->layout('layouts.app');
    }
}
