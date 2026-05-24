<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\JobApplication;
use Livewire\Component;

class JobKanbanBoard extends Component
{
    public $statusOptions = [
        'On Process',
        'Accepted',
        'Rejected'
    ];

    public $platformOptions = [
        '9cv9', 'Cake: Cari Lowongan', 'Dealls', 'Disnakerja.com', 'Email', 'Fiverr', 'Freelancer',
        'Glassdoor', 'Glints', 'Google Forms', 'Indeed', 'JobStreet', 'Jobseeker App', 'JobsDB',
        'Jora', 'Kalibrr', 'Karir.com', 'Karirhub (SIAPkerja)', 'KitaLulus', 'LinkedIn', 'Loker.id',
        'Microsoft Forms', 'NusaCrowd', 'Pintarnya.com', 'SkillAcademy', 'SkillTrade', 'Talentics',
        'Tech in Asia', 'Urbanhire', 'Website Company', 'Other'
    ];

    public $careerLevelOptions = [
        'Intern', 'Full Time', 'Contract', 'MT', 'Freelance'
    ];

    public $recruitmentStageOptions = [
        'Applied', 'Follow Up', 'Assessment Test', 'Psychotest', 'HR - Interview',
        'User - Interview', 'LGD', 'Presentation Round', 'Offering', 'Not Processed'
    ];
    
    public $search = '';
    public $platformFilter = '';
    public $careerLevelFilter = '';
    public $recruitmentStageFilter = '';
    public $showAdvancedFilters = false;
    public $showArchived = false;
    
    // Ghosting Follow Up Properties
    public $showFollowUpModal = false;
    public $followUpDraft = '';
    public $currentFollowUpJobId = null;
    public $followUpJobId = null;
    public $isGeneratingFollowUp = false;
    
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
    }

    public function updatingSearch() { }
    public function updatingPlatformFilter() { }

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

    public function confirmDelete($jobId)
    {
        $job = JobApplication::where('id', $jobId)->where('user_id', auth()->id())->first();
        if ($job) {
            $this->dispatch('confirm-action', [
                'title' => 'Delete Application?',
                'message' => "Are you sure you want to remove your application for {$job->company_name} from your board? This action cannot be undone.",
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
            $this->dispatch('showNotification', [
                'type' => 'warning',
                'title' => 'Job Application Deleted',
                'message' => "Successfully deleted application for {$companyName}",
                'duration' => 3000
            ]);
            $this->dispatch('job-deleted');
        }
    }

    public function updateStatus($jobId, $newStatus)
    {
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
        $archivedCount = JobApplication::where('user_id', auth()->id())->where('is_archived', true)->count();

        $statuses = collect($this->statusOptions)->map(function ($status) {
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
            if ($this->recruitmentStageFilter) $query->where('recruitment_stage', $this->recruitmentStageFilter);

            $jobApplications = $query->orderBy('is_pinned', 'desc')->orderBy('application_date', 'desc')->get();

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
            'Applied' => '#3B82F6', 'Follow Up' => '#06B6D4', 'Assessment Test' => '#8B5CF6',
            'Psychotest' => '#6366F1', 'HR - Interview' => '#10B981', 'User - Interview' => '#059669',
            'LGD' => '#F59E0B', 'Presentation Round' => '#F97316', 'Offering' => '#14B8A6', 'Not Processed' => '#EF4444',
        ];
        return $colors[$stage] ?? '#6B7280';
    }

    public function openFollowUpModal($jobId)
    {
        $this->followUpJobId = $jobId;
        $this->showFollowUpModal = true;
        $this->followUpDraft = '';
        
        // Dispatch event to trigger generation AFTER the modal renders
        $this->dispatch('trigger-ai-generation');
    }

    public function closeFollowUpModal()
    {
        $this->showFollowUpModal = false;
        $this->followUpJobId = null;
        $this->followUpDraft = '';
    }

    public function generateFollowUp()
    {
        if (!$this->followUpJobId) return;
        $job = JobApplication::where('user_id', Auth::id())->findOrFail($this->followUpJobId);

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
            // Using the existing Vercel AI API
            $response = Http::timeout(60)->post('https://ai-analyzer-seven.vercel.app/generate-cl', $aiPayload);

            if ($response->successful()) {
                $responseBody = $response->json();
                $this->followUpDraft = $responseBody['cover_letter'] ?? $responseBody['result'] ?? '';
            } else {
                $this->followUpDraft = "Maaf, gagal membuat draft email. Silakan coba lagi nanti.";
            }
        } catch (\Exception $e) {
            Log::error('AI Follow Up Error: ' . $e->getMessage());
            $this->followUpDraft = "Terjadi kesalahan saat menghubungi server AI.";
        }

        $this->isGeneratingFollowUp = false;
    }
}
