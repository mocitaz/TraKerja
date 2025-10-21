<?php

namespace App\Livewire;

use App\Models\JobApplication;
use Livewire\Component;
use Livewire\WithPagination;

class JobTableList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortField = 'application_date';
    public $sortDirection = 'desc';
    public $perPage = 20;

    public $statusOptions = [
        'On Process',
        'Declined',
        'Accepted'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'application_date'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 20],
    ];

    protected $listeners = [
        'job-deleted' => '$refresh',
        'job-saved' => '$refresh',
        'status-updated' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
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
        $query = JobApplication::where('user_id', auth()->id());

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

        $jobApplications = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.job-table-list', [
            'jobApplications' => $jobApplications,
        ]);
    }

}
