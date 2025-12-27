<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\JobApplication;
use Livewire\Component;

class AnalyticsCards extends Component
{
    public $onProcessCount = 0;
    public $offeringAcceptedCount = 0;
    public $declinedCount = 0;
    public $totalApplications = 0;

    protected $listeners = [
        'job-saved' => 'loadAnalytics',
        'job-deleted' => 'loadAnalytics',
        'status-updated' => 'loadAnalytics',
    ];

    public function mount()
    {
        $this->loadAnalytics();
    }

    public function loadAnalytics()
    {
        \Log::info('AnalyticsCards: loadAnalytics called');
        
        $userId = auth()->id();
        
        // On Process (Aktif): Hitung jumlah lamaran dengan application_status = 'On Process'
        $this->onProcessCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'On Process')
            ->where('is_archived', false)
            ->count();

        // Offering / Accepted: Hitung jumlah lamaran yang memiliki recruitment_stage = 'Offering' ATAU application_status = 'Accepted' ATAU application_status = 'Offering'
        $this->offeringAcceptedCount = JobApplication::where('user_id', $userId)
            ->where(function($query) {
                $query->where('recruitment_stage', 'Offering')
                      ->orWhere('application_status', 'Accepted')
                      ->orWhere('application_status', 'Offering');
            })
            ->where('is_archived', false)
            ->count();

        // Declined: Hitung jumlah lamaran dengan application_status = 'Declined' saja
        // CATATAN: Hanya menghitung yang benar-benar Declined, TIDAK termasuk Not Processed
        // Not Processed adalah recruitment_stage, bukan application_status, jadi tidak dihitung di sini
        $this->declinedCount = JobApplication::where('user_id', $userId)
            ->where('application_status', 'Declined')
            ->count();

        // Total Applications
        $this->totalApplications = JobApplication::where('user_id', $userId)
            ->where('is_archived', false)
            ->count();
        
        \Log::info('AnalyticsCards: Updated counts', [
            'onProcess' => $this->onProcessCount,
            'offeringAccepted' => $this->offeringAcceptedCount,
            'declined' => $this->declinedCount,
            'total' => $this->totalApplications
        ]);
    }

    public function render()
    {
        return view('livewire.analytics-cards');
    }
}
