<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\JobApplication;
use Livewire\Component;
use Livewire\WithPagination;

class JobTableList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $platformFilter = '';
    public $careerLevelFilter = '';
    public $recruitmentStageFilter = '';
    public $locationFilter = '';
    public $dateFromFilter = '';
    public $dateToFilter = '';
    public $sortField = 'application_date';
    public $sortDirection = 'desc';
    public $perPage = 20;
    public $showAdvancedFilters = false;
    public $showArchived = false;

    public $statusOptions = [
        'On Process',
        'Declined',
        'Accepted'
    ];

    public $platformOptions = [
        '9cv9',
        'Cake: Cari Lowongan',
        'Dealls',
        'Disnakerja.com',
        'Email',
        'Fiverr',
        'Freelancer',
        'Glassdoor',
        'Glints',
        'Google Forms',
        'Indeed',
        'JobStreet',
        'Jobseeker App',
        'JobsDB',
        'Jora',
        'Kalibrr',
        'Karir.com',
        'Karirhub (SIAPkerja)',
        'KitaLulus',
        'LinkedIn',
        'Loker.id',
        'Microsoft Forms',
        'NusaCrowd',
        'Pintarnya.com',
        'SkillAcademy',
        'SkillTrade',
        'Talentics',
        'Tech in Asia',
        'Urbanhire',
        'Website Company',
        'Other'
    ];

    public $careerLevelOptions = [
        'Intern',
        'Full Time',
        'Contract',
        'MT',
        'Freelance'
    ];

    public $recruitmentStageOptions = [
        'Applied',
        'Follow Up',
        'Assessment Test',
        'Psychotest',
        'HR - Interview',
        'User - Interview',
        'LGD',
        'Presentation Round',
        'Offering',
        'Not Processed'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'platformFilter' => ['except' => ''],
        'careerLevelFilter' => ['except' => ''],
        'recruitmentStageFilter' => ['except' => ''],
        'locationFilter' => ['except' => ''],
        'dateFromFilter' => ['except' => ''],
        'dateToFilter' => ['except' => ''],
        'sortField' => ['except' => 'application_date'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 20],
        'showArchived' => ['except' => false],
    ];

    protected $listeners = [
        'job-deleted' => '$refresh',
        'job-saved' => '$refresh',
        'status-updated' => '$refresh',
        'job-pinned' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPlatformFilter()
    {
        $this->resetPage();
    }

    public function updatingCareerLevelFilter()
    {
        $this->resetPage();
    }

    public function updatingRecruitmentStageFilter()
    {
        $this->resetPage();
    }

    public function updatingLocationFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFromFilter()
    {
        $this->resetPage();
    }

    public function updatingDateToFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingShowArchived()
    {
        $this->resetPage();
    }

    public function toggleArchived()
    {
        $this->showArchived = !$this->showArchived;
        $this->resetPage();
    }

    public function toggleAdvancedFilters()
    {
        $this->showAdvancedFilters = !$this->showAdvancedFilters;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->platformFilter = '';
        $this->recruitmentStageFilter = '';
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function edit($jobId)
    {
        $this->dispatch('edit-job', jobId: $jobId)->to('job-application-form');
    }

    public function togglePin($jobId)
    {
        $job = JobApplication::where('id', $jobId)
            ->where('user_id', auth()->id())
            ->first();

        if ($job) {
            // If trying to pin, check if user already has 5 pinned items
            if (!$job->is_pinned) {
                $pinnedCount = JobApplication::where('user_id', auth()->id())
                    ->where('is_pinned', true)
                    ->count();
                
                if ($pinnedCount >= 5) {
                    $this->dispatch('showNotification', [
                        'type' => 'error',
                        'title' => 'Pin Limit Reached',
                        'message' => 'You can only pin up to 5 jobs. Unpin another job first.',
                        'duration' => 3000
                    ]);
                    return;
                }
            }
            
            $job->update(['is_pinned' => !$job->is_pinned]);
            
            $message = $job->is_pinned 
                ? "Pinned {$job->company_name} to top" 
                : "Unpinned {$job->company_name}";
            
            $this->dispatch('showNotification', [
                'type' => 'info',
                'title' => $job->is_pinned ? 'Job Pinned' : 'Job Unpinned',
                'message' => $message,
                'duration' => 2000
            ]);
            
            $this->dispatch('job-pinned');
        }
    }

    public function delete($jobId)
    {
        \Log::info('JobTableList delete method called with jobId:', ['jobId' => $jobId]);
        
        $job = JobApplication::where('id', $jobId)
            ->where('user_id', auth()->id())
            ->first();

        if ($job) {
            \Log::info('Job found, deleting...', ['jobId' => $jobId, 'company' => $job->company_name]);
            $companyName = $job->company_name;
            $job->delete();
            session()->flash('message', 'Job application deleted successfully!');
            
            // Send notification for job deletion
            $this->dispatch('showNotification', [
                'type' => 'warning',
                'title' => 'Job Application Deleted',
                'message' => "Successfully deleted application for {$companyName}",
                'duration' => 3000
            ]);
            
            // Dispatch global events for auto-refresh
            $this->dispatch('job-deleted');
            \Log::info('Job deleted successfully');
        } else {
            \Log::warning('Job not found for deletion', ['jobId' => $jobId, 'userId' => auth()->id()]);
        }
    }

    public function getStatusColor($status)
    {
        $colors = [
            'On Process' => '#3B82F6',
            'Declined' => '#EF4444',
            'Accepted' => '#047857',
        ];

        return $colors[$status] ?? '#6B7280';
    }

    public function render()
    {
        // Get archived count
        $archivedCount = JobApplication::where('user_id', auth()->id())
            ->where('is_archived', true)
            ->count();

        // Build query based on showArchived flag
        $query = JobApplication::where('user_id', auth()->id())
            ->where('is_archived', $this->showArchived);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('company_name', 'like', '%' . $this->search . '%')
                  ->orWhere('position', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('application_status', $this->statusFilter);
        }

        if ($this->platformFilter) {
            $query->where('platform', $this->platformFilter);
        }

        if ($this->recruitmentStageFilter) {
            $query->where('recruitment_stage', $this->recruitmentStageFilter);
        }

        $jobApplications = $query->orderBy('is_pinned', 'desc') // Pinned items first
            ->orderBy($this->sortField, $this->sortDirection)
            ->orderBy('created_at', 'desc') // Tertiary sort by creation time
            ->paginate($this->perPage);

        return view('livewire.job-table-list', [
            'jobApplications' => $jobApplications,
            'archivedCount' => $archivedCount,
        ]);
    }

}
