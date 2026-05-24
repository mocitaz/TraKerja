<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Models\JobApplication;
use Livewire\Component;
use Livewire\WithPagination;

class JobTableList extends Component
{
    use WithPagination;

    /**
     * Whitelist of columns allowed for sorting.
     * Prevents SQL injection via URL-manipulated sortField.
     */
    private const ALLOWED_SORT_FIELDS = [
        'application_date',
        'company_name',
        'position',
        'application_status',
        'recruitment_stage',
        'platform',
        'created_at',
    ];

    private const ALLOWED_SORT_DIRECTIONS = ['asc', 'desc'];

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
    public $perPage = 30;
    public $showAdvancedFilters = false;
    public $showArchived = false;

    // Ghosting Follow Up Properties
    public $showFollowUpModal = false;
    public $followUpDraft = '';
    public $followUpJobId = null;
    public $isGeneratingFollowUp = false;

    public $statusOptions = [
        'Applied',
        'On Process',
        'Interview',
        'Offering',
        'Accepted',
        'Rejected'
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
        'perPage' => ['except' => 30],
        'showArchived' => ['except' => false],
    ];

    protected $listeners = [
        'job-deleted' => '$refresh',
        'job-saved' => '$refresh',
        'status-updated' => '$refresh',
        'job-pinned' => '$refresh',
        'delete-confirmed' => 'delete',
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
        // SECURITY: Reject any field not in the whitelist
        if (!in_array($field, self::ALLOWED_SORT_FIELDS, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Livewire hook: sanitize sortField if tampered via URL query string.
     */
    public function updatedSortField($value)
    {
        if (!in_array($value, self::ALLOWED_SORT_FIELDS, true)) {
            $this->sortField = 'application_date';
        }
    }

    /**
     * Livewire hook: sanitize sortDirection if tampered via URL query string.
     */
    public function updatedSortDirection($value)
    {
        if (!in_array($value, self::ALLOWED_SORT_DIRECTIONS, true)) {
            $this->sortDirection = 'desc';
        }
    }

    public function edit($jobId)
    {
        $this->dispatch('edit-job', jobId: $jobId);
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

    public function confirmDelete($jobId)
    {
        $job = JobApplication::where('id', $jobId)->where('user_id', auth()->id())->first();
        if ($job) {
            $this->dispatch('confirm-action', [
                'title' => 'Delete Application?',
                'message' => "Are you sure you want to remove your application for {$job->company_name}? This action cannot be undone.",
                'btnText' => 'Delete Now',
                'onConfirm' => 'delete-confirmed',
                'params' => ['jobId' => $jobId]
            ]);
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
            'Applied' => '#64748b', 
            'On Process' => '#3B82F6', 
            'Interview' => '#8B5CF6',
            'Offering' => '#14B8A6', 
            'Accepted' => '#10B981', 
            'Rejected' => '#EF4444',
            'Declined' => '#EF4444',
        ];

        return $colors[$status] ?? '#6B7280';
    }

    public function getStageColor($stage)
    {
        $colors = [
            'Applied' => '#3B82F6',           // Blue - baru apply
            'Follow Up' => '#06B6D4',          // Cyan - sedang follow up
            'Assessment Test' => '#8B5CF6',    // Purple - test assessment
            'Psychotest' => '#6366F1',         // Indigo - test psikologi
            'HR - Interview' => '#10B981',     // Green - interview HR
            'User - Interview' => '#059669',    // Emerald - interview user
            'LGD' => '#F59E0B',                // Amber - group discussion
            'Presentation Round' => '#F97316', // Orange - presentasi
            'Offering' => '#14B8A6',           // Teal - offer
            'Not Processed' => '#EF4444',     // Red - tidak diproses (mirip declined)
        ];

        return $colors[$stage] ?? '#6B7280';
    }

    public function getCareerLevelColor($level)
    {
        $colors = [
            'Intern' => '#EC4899',           // Pink - intern/magang
            'Full Time' => '#10B981',         // Green - full time
            'Contract' => '#F59E0B',          // Amber - kontrak
            'MT' => '#8B5CF6',                // Purple - management trainee
            'Freelance' => '#06B6D4',         // Cyan - freelance
        ];

        return $colors[$level] ?? '#6B7280';
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

        // SECURITY: Final guard — ensure sortField and sortDirection are safe before query
        $safeSortField = in_array($this->sortField, self::ALLOWED_SORT_FIELDS, true)
            ? $this->sortField
            : 'application_date';
        $safeSortDirection = in_array($this->sortDirection, self::ALLOWED_SORT_DIRECTIONS, true)
            ? $this->sortDirection
            : 'desc';

        $jobApplications = $query->orderBy('is_pinned', 'desc') // Pinned items first
            ->orderBy($safeSortField, $safeSortDirection)
            ->orderBy('created_at', 'desc') // Tertiary sort by creation time
            ->paginate($this->perPage);

        return view('livewire.job-table-list', [
            'jobApplications' => $jobApplications,
            'archivedCount' => $archivedCount,
        ]);
    }

    public function closeFollowUpModal()
    {
        $this->showFollowUpModal = false;
        $this->followUpJobId = null;
        $this->followUpDraft = '';
    }

    public function generateFollowUp($jobId)
    {
        $this->followUpJobId = $jobId;
        $job = JobApplication::where('id', $jobId)->where('user_id', auth()->id())->first();
        if (!$job) {
            $this->closeFollowUpModal();
            return;
        }

        $this->isGeneratingFollowUp = true;

        $prompt = "CRITICAL INSTRUCTION: DO NOT WRITE A COVER LETTER. Write a highly professional follow-up email to HR. Context: The applicant applied for this position 14 days ago and hasn't heard back. The email should politely ask for a status update. Use formal Indonesian language. Keep it concise, empathetic, and professional.";
        
        $context = [
            'experiences' => [
                [
                    'company_name' => $job->company_name,
                    'position' => $job->position,
                    'description' => "Applied on " . $job->application_date->format('d M Y') . ". Application stage: " . ($job->recruitment_stage ?: $job->application_status),
                ]
            ]
        ];

        $aiPayload = [
            'company_name' => $job->company_name,
            'job_title' => $job->position,
            'job_description' => $prompt,
            'language' => 'id',
            'tone' => 'professional',
            'length' => 'short',
            'highlight_focus' => 'Requesting status update on application',
            'candidate_context' => $context,
        ];

        try {
            $response = Http::timeout(60)->post('https://ai-analyzer-seven.vercel.app/generate-cl', $aiPayload);

            if ($response->successful()) {
                $responseBody = $response->json();
                $this->followUpDraft = $responseBody['cover_letter'] ?? $responseBody['result'] ?? '';
                // Open modal AFTER draft is ready
                $this->showFollowUpModal = true;
            } else {
                $this->followUpDraft = "Maaf, gagal membuat draft email. Silakan coba lagi nanti.";
                $this->showFollowUpModal = true;
            }
        } catch (\Exception $e) {
            Log::error('AI Follow Up Error: ' . $e->getMessage());
            $this->followUpDraft = "Terjadi kesalahan saat menghubungi server AI.";
            $this->showFollowUpModal = true;
        }

        $this->isGeneratingFollowUp = false;
    }
}
