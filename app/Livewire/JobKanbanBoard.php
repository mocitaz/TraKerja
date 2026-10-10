<?php

namespace App\Livewire;

use App\Models\JobApplication;
use App\Support\JobApplicationStatus;
use Livewire\Component;
use Livewire\WithPagination;

class JobKanbanBoard extends Component
{
    use WithPagination;
    use \App\Livewire\Concerns\DraftsApplicationFollowUp;
    public $statusOptions = JobApplicationStatus::OPTIONS;

    public $platformOptions = \App\Support\PlatformCatalog::OPTIONS;

    public $careerLevelOptions = [
        'Intern', 'Full Time', 'Contract', 'MT', 'Freelance'
    ];

    public $recruitmentStageOptions = JobApplicationStatus::STAGES;
    
    public $search = '';
    public $platformFilter = '';
    public $careerLevelFilter = '';
    public $recruitmentStageFilter = '';
    public $showAdvancedFilters = false;
    public $showArchived = false;
    
    public $currentFollowUpJobId = null;
    
    private $lastUpdateTime = [];

    protected $listeners = [
        'updateStatus' => 'updateStatus',
        'job-deleted' => '$refresh',
        'job-saved' => '$refresh',
        'status-updated' => '$refresh',
        'job-pinned' => '$refresh',
        'delete-confirmed' => 'delete',
    ];

    public function edit($jobId)
    {
        $this->dispatch('edit-job', jobId: $jobId);
    }

    public function toggleArchived()
    {
        $this->showArchived = !$this->showArchived;
        $this->resetColumns();
    }

    public function updatingSearch() { $this->resetColumns(); }
    public function updatingPlatformFilter() { $this->resetColumns(); }
    public function updatingCareerLevelFilter() { $this->resetColumns(); }
    public function updatingRecruitmentStageFilter() { $this->resetColumns(); }

    private function resetColumns(): void
    {
        foreach (JobApplicationStatus::OPTIONS as $status) {
            $this->resetPage('column_'.strtolower(str_replace(' ', '_', $status)));
        }
    }

    public function toggleAdvancedFilters()
    {
        $this->showAdvancedFilters = !$this->showAdvancedFilters;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->platformFilter = '';
        $this->recruitmentStageFilter = '';
        $this->careerLevelFilter = '';
        $this->resetColumns();
    }

    public function confirmDelete($jobId)
    {
        $job = JobApplication::where('id', $jobId)->where('user_id', auth()->id())->first();
        if ($job) {
            $this->dispatch('confirm-action', [
                'title' => 'Delete Application?',
                'message' => "The application data for {$job->company_name} will be permanently deleted (10 XP will be deducted). You can also edit the application details instead.",
                'btnText' => 'Delete Now',
                'onConfirm' => 'delete-confirmed',
                'params' => ['jobId' => $jobId]
            ]);
        }
    }

    public function delete($jobId)
    {
        $job = JobApplication::where('id', $jobId)->where('user_id', auth()->id())->first();
        if ($job) {
            $companyName = $job->company_name;
            $job->delete();
            
            // Deduct XP on delete
            $user = auth()->user();
            if (method_exists($user, 'deductXP')) {
                $user->deductXP(10);
            }
            
            $this->dispatch('showNotification', [
                'type' => 'warning',
                'title' => 'Job Application Deleted',
                'message' => "Successfully deleted application for {$companyName}. 10 XP deducted.",
                'duration' => 3000
            ]);
            $this->dispatch('job-deleted');
            
            // Refresh goals cadence manually to update UI
            $this->dispatch('jobAdded');
        }
    }

    public function updateStatus($jobId, $newStatus)
    {
        \Illuminate\Support\Facades\Validator::make(['newStatus' => $newStatus], [
            'newStatus' => JobApplicationStatus::VALIDATION_RULE,
        ])->validate();
        $job = JobApplication::where('id', $jobId)->where('user_id', auth()->id())->first();
        if ($job) {
            if ($job->application_status === $newStatus) return;
            
            $job->update(['application_status' => $newStatus]);
            $this->dispatch('status-updated');

            if ($newStatus === 'Accepted') {
                $this->dispatch('confetti');
            }

            $this->dispatch('showNotification', [
                'type' => $newStatus === 'Accepted' ? 'success' : 'info',
                'title' => $newStatus === 'Accepted' ? 'Congratulations' : 'Status Updated',
                'message' => $newStatus === 'Accepted' 
                    ? "Incredible news! Your application for {$job->company_name} was accepted" 
                    : "Application for {$job->company_name} updated to {$newStatus}",
                'duration' => $newStatus === 'Accepted' ? 6000 : 3000
            ]);
        }
    }

    public function render()
    {
        // Trigger auto archive on render
        JobApplication::autoArchiveUserJobs(auth()->id());

        $archivedCount = JobApplication::where('user_id', auth()->id())->where('is_archived', true)->count();

        $statuses = collect(JobApplicationStatus::OPTIONS)->map(function ($status) {
            $query = JobApplication::where('user_id', auth()->id())
                ->where('application_status', $status)
                ->where('is_archived', $this->showArchived);

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                      ->orWhere('position', 'like', '%' . $this->search . '%')
                      ->orWhere('location', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->platformFilter) $query->where('platform', $this->platformFilter);
            if ($this->careerLevelFilter) $query->where('career_level', $this->careerLevelFilter);
            if ($this->recruitmentStageFilter) $query->where('recruitment_stage', $this->recruitmentStageFilter);

            $total = $query->count();
            $pageName = 'column_'.strtolower(str_replace(' ', '_', $status));
            $page = max(1, min((int) $this->getPage($pageName), max(1, (int) ceil($total / 30))));
            $this->paginators[$pageName] = $page;
            $jobApplications = $query->orderBy('is_pinned', 'desc')->orderBy('application_date', 'desc')->orderBy('id', 'desc')
                ->paginate(30, ['*'], $pageName, $page, $total);

            return (object) [
                'name' => $status,
                'color_code' => $this->getStatusColor($status),
                'jobApplications' => $jobApplications,
                'total' => $total,
            ];
        });

        return view('livewire.job-kanban-board', [
            'statuses' => $statuses,
            'archivedCount' => $archivedCount,
        ]);
    }

    private function getStatusColor($status)
    {
        $colors = [
            'Applied' => '#64748b', 
            'On Process' => '#3B82F6', 
            'Interview' => '#8B5CF6',
            'Offering' => '#14B8A6', 
            'Accepted' => '#10B981', 
            'Rejected' => '#EF4444',
        ];
        return $colors[$status] ?? '#6B7280';
    }

    public function getStageColor($stage)
    {
        $colors = [
            'Applied' => '#3B82F6', 'Follow Up' => '#06B6D4', 'Assessment Test' => '#8B5CF6',
            'Psychotest' => '#6366F1', 'HR - Interview' => '#10B981', 'User - Interview' => '#059669',
            'LGD' => '#F59E0B', 'Presentation Round' => '#F97316', 'Offering' => '#14B8A6', 'Not Processed' => '#EF4444',
        ];
        return $colors[$stage] ?? '#6B7280';
    }


}
