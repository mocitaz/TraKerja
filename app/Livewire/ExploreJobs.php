<?php

namespace App\Livewire;

use App\Models\JobPosting;
use Livewire\Component;
use Livewire\WithPagination;

class ExploreJobs extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedPlatform = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedPlatform' => ['except' => ''],
    ];

    public function mount()
    {
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->role === 'admin')) {
            return redirect()->route('admin.index');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedPlatform()
    {
        $this->resetPage();
    }

    public function reportExpired($id)
    {
        $posting = JobPosting::find($id);
        
        if ($posting) {
            $posting->increment('report_dead_count');
            
            if ($posting->report_dead_count >= 3) {
                $posting->update(['status' => 'reported_dead']);
                
                // Spin up a high priority validation task or just handle it
                // Dispatching background verification or archiving
                $posting->update(['status' => 'closed']); // Archive immediately to showcase loop
                
                session()->flash('info_' . $id, 'This listing has been reported multiple times and is now archived.');
            } else {
                session()->flash('success_' . $id, 'Terima kasih atas laporan Anda! Link ini akan segera diverifikasi.');
            }
        }
    }

    public function render()
    {
        $query = JobPosting::where('status', 'active');

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

        $postings = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('livewire.explore-jobs', [
            'postings' => $postings
        ])->layout('layouts.app');
    }
}
