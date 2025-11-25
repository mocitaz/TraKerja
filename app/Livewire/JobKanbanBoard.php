<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\JobApplication;
use Livewire\Component;

class JobKanbanBoard extends Component
{
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
    
    public $search = '';
    public $platformFilter = '';
    public $careerLevelFilter = '';
    public $recruitmentStageFilter = '';
    public $locationFilter = '';
    public $dateFromFilter = '';
    public $dateToFilter = '';
    public $showAdvancedFilters = false;
    public $showArchived = false;
    
    // Track last update to prevent spam
    private $lastUpdateTime = [];

    protected $listeners = [
        'updateStatus' => 'updateStatus',
        'job-deleted' => '$refresh',
        'job-saved' => '$refresh',
        'status-updated' => '$refresh',
        'job-pinned' => '$refresh',
    ];

    public function updatingSearch()
    {
        // Reset not needed for kanban
    }

    public function updatingPlatformFilter()
    {
        // Reset not needed for kanban
    }

    public function updatingCareerLevelFilter()
    {
        // Reset not needed for kanban
    }

    public function updatingRecruitmentStageFilter()
    {
        // Reset not needed for kanban
    }

    public function updatingLocationFilter()
    {
        // Reset not needed for kanban
    }

    public function updatingDateFromFilter()
    {
        // Reset not needed for kanban
    }

    public function updatingDateToFilter()
    {
        // Reset not needed for kanban
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
    }

    public function toggleArchived()
    {
        $this->showArchived = !$this->showArchived;
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

    public function updateStatus($jobId, $newStatus)
    {
        \Log::info('updateStatus called', ['jobId' => $jobId, 'newStatus' => $newStatus]);
        
        $job = JobApplication::where('id', $jobId)
            ->where('user_id', auth()->id())
            ->first();

        if ($job) {
            // Check if status is actually different to prevent unnecessary updates
            if ($job->application_status === $newStatus) {
                \Log::info('Status unchanged, skipping update', ['jobId' => $jobId, 'currentStatus' => $job->application_status]);
                return;
            }
            
            // Check if this job was updated recently (within 3 seconds) to prevent spam
            $now = time();
            $lastUpdate = $this->lastUpdateTime[$jobId] ?? 0;
            if (($now - $lastUpdate) < 3) {
                \Log::info('Job updated too recently, skipping notification', ['jobId' => $jobId, 'lastUpdate' => $lastUpdate, 'now' => $now]);
                return;
            }
            
            $oldStatus = $job->application_status;
            $job->update(['application_status' => $newStatus]);
            $this->lastUpdateTime[$jobId] = $now; // Track update time
            
            \Log::info('Job application status updated successfully', ['jobId' => $jobId, 'oldStatus' => $oldStatus, 'newStatus' => $newStatus]);
            
            // Dispatch global events for auto-refresh
            $this->dispatch('status-updated');
            
            // Only send notification if status actually changed and not too recent
            $notificationKey = 'status_updated_' . $jobId . '_' . $newStatus . '_' . now()->format('Y-m-d-H-i');
            if (!session()->has($notificationKey)) {
                $this->dispatch('showNotification', [
                    'type' => 'info',
                    'title' => 'Status Updated',
                    'message' => "Application for {$job->company_name} updated to {$newStatus}",
                    'duration' => 3000
                ]);
                session()->put($notificationKey, true);
            }
            
            $this->dispatch('status-updated');
        } else {
            \Log::warning('Job not found or not authorized', ['jobId' => $jobId]);
        }
    }


    public function render()
    {
        // Get archived count
        $archivedCount = JobApplication::where('user_id', auth()->id())
            ->where('is_archived', true)
            ->count();

        $statuses = collect($this->statusOptions)->map(function ($status) {
            $query = JobApplication::where('user_id', auth()->id())
                ->where('application_status', $status)
                ->where('is_archived', $this->showArchived);

            // Apply filters
            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                      ->orWhere('position', 'like', '%' . $this->search . '%')
                      ->orWhere('location', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->platformFilter) {
                $query->where('platform', $this->platformFilter);
            }

            if ($this->recruitmentStageFilter) {
                $query->where('recruitment_stage', $this->recruitmentStageFilter);
            }

            $jobApplications = $query->orderBy('is_pinned', 'desc') // Pinned items first
                ->orderBy('application_date', 'desc')
                ->get();

            return (object) [
                'name' => $status,
                'color_code' => $this->getStatusColor($status),
                'jobApplications' => $jobApplications
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
            'On Process' => '#3B82F6',
            'Declined' => '#EF4444',
            'Accepted' => '#047857',
        ];

        return $colors[$status] ?? '#6B7280';
    }
}
