<?php

namespace App\Livewire;

use App\Models\JobPosting;
use Livewire\Component;
use Livewire\WithPagination;

class ExploreJobs extends Component
{
    use WithPagination;

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
    }

    public function resetLocationFilter()
    {
        $this->selectedProvince = '';
        $this->selectedLocation = '';
        $this->resetPage();
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
        session()->flash('report_success_' . $id, $accepted
            ? 'Report submitted. The listing will be checked.'
            : 'Your report is already recorded, or the listing is no longer active.');
    }

    public function initiateTrackJob($id)
    {
        $posting = JobPosting::with('scraperSource')->where('status', 'active')->findOrFail($id);
        if ($posting) {
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
        
        if ($posting) {
            $exists = \App\Models\JobApplication::where('user_id', auth()->id())
                ->where('company_name', $posting->company_name)
                ->where('position', $posting->title)
                ->exists();
                
            if ($exists) {
                session()->flash('track_info_' . $id, 'Already in your tracker.');
                return;
            }

            $portalName = $posting->source_platform;

            \App\Models\JobApplication::create([
                'user_id' => auth()->id(),
                'company_name' => $posting->company_name,
                'position' => $posting->title,
                'location' => $posting->location ?: 'Indonesia',
                'platform' => $portalName,
                'platform_link' => $posting->raw_url,
                'application_status' => 'On Process',
                'recruitment_stage' => 'Applied',
                'application_date' => now()->format('Y-m-d'),
                'notes' => 'Lowongan disimpan secara otomatis dari halaman Cari Lowongan.',
            ]);

            session()->flash('track_success_' . $id, 'Added to your tracker.');
        }
    }

    public function render()
    {
        $maintenanceDefault = !app()->environment('testing');
        if (\App\Models\Setting::get('explore_maintenance', $maintenanceDefault)) {
            return view('explore.maintenance')->layout('layouts.app');
        }

        $query = JobPosting::with('scraperSource')->where('status', 'active');

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
            ->whereIn('company_name', $postings->pluck('company_name'))->get(['id', 'company_name', 'position']);
        $savedJobs = [];
        foreach ($postings as $posting) {
            $saved = $applications->first(fn ($application) => $application->company_name === $posting->company_name && $application->position === $posting->title);
            if ($saved) $savedJobs[$posting->id] = $saved->id;
        }
        $reportedIds = \Illuminate\Support\Facades\DB::table('job_posting_reports')->where('user_id', auth()->id())
            ->whereIn('job_posting_id', $postings->pluck('id'))->pluck('job_posting_id')->all();

        $fieldsList = \App\Helpers\CategoryHelper::getSektorList();
        sort($fieldsList);

        $majorsList = $this->majors;
        sort($majorsList);

        return view('livewire.explore-jobs', [
            'postings' => $postings,
            'savedJobs' => $savedJobs,
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
