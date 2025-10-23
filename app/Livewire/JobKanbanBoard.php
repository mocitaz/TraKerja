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
    
    // Track last update to prevent spam
    private $lastUpdateTime = [];

    protected $listeners = [
        'updateStatus' => 'updateStatus',
        'job-deleted' => '$refresh',
        'job-saved' => '$refresh',
        'status-updated' => '$refresh',
    ];

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
        $statuses = collect($this->statusOptions)->map(function ($status) {
            $jobApplications = JobApplication::where('user_id', auth()->id())
                ->where('application_status', $status)
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
