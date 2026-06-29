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
    public $selectedField = '';
    public $selectedMajor = '';
    public $selectedWorkType = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedPlatform' => ['except' => ''],
        'selectedField' => ['except' => ''],
        'selectedMajor' => ['except' => ''],
        'selectedWorkType' => ['except' => ''],
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

    public function updatingSelectedField()
    {
        $this->resetPage();
    }

    public function updatingSelectedMajor()
    {
        $this->resetPage();
    }

    public function updatingSelectedWorkType()
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
                
                session()->flash('report_info_' . $id, 'This listing has been reported multiple times and is now archived.');
            } else {
                session()->flash('report_success_' . $id, 'Laporan Diterima!');
            }
        }
    }

    public function trackJob($id)
    {
        $posting = JobPosting::find($id);
        
        if ($posting) {
            $exists = \App\Models\JobApplication::where('user_id', auth()->id())
                ->where('company_name', $posting->company_name)
                ->where('position', $posting->title)
                ->exists();
                
            if ($exists) {
                session()->flash('track_info_' . $id, 'Sudah ditambahkan!');
                return;
            }

            \App\Models\JobApplication::create([
                'user_id' => auth()->id(),
                'company_name' => $posting->company_name,
                'position' => $posting->title,
                'location' => 'Jakarta, Indonesia',
                'platform' => str_contains($posting->scraperSource->target_domain, 'linkedin') ? 'LinkedIn' : (str_contains($posting->scraperSource->target_domain, 'jobstreet') ? 'JobStreet' : 'Kalibrr'),
                'platform_link' => $posting->raw_url,
                'application_status' => 'Applied',
                'recruitment_stage' => 'Applied',
                'application_date' => now()->format('Y-m-d'),
                'notes' => 'Lowongan disimpan secara otomatis dari halaman Explore Jobs.',
            ]);

            session()->flash('track_success_' . $id, 'Disimpan ke Tracker!');
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

        if (!empty($this->selectedField)) {
            $query->where('category_field', $this->selectedField);
        }

        if (!empty($this->selectedMajor)) {
            $query->where('category_major', $this->selectedMajor);
        }

        if (!empty($this->selectedWorkType)) {
            $query->where('work_type', $this->selectedWorkType);
        }

        $postings = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('livewire.explore-jobs', [
            'postings' => $postings
        ])->layout('layouts.app');
    }
}
