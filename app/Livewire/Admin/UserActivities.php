<?php

namespace App\Livewire\Admin;

use App\Models\UserActivity;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class UserActivities extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = 'all';
    public $filterStatus = 'all';
    public $perPage = 15;
    
    public $showSettingsModal = false;
    public $pruneDays = 90;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => 'all'],
        'filterStatus' => ['except' => 'all'],
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
    
    public function loadSettings()
    {
        $setting = Setting::where('key', 'activity_log_prune_days')->first();
        $this->pruneDays = $setting ? (int) $setting->value : 90;
    }
    
    public function saveSettings()
    {
        $this->validate([
            'pruneDays' => 'required|integer|min:1',
        ]);
        
        Setting::updateOrCreate(
            ['key' => 'activity_log_prune_days'],
            ['value' => (string) $this->pruneDays]
        );
        
        $this->showSettingsModal = false;
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Pengaturan Tersimpan',
            'message' => 'Lama penyimpanan log aktivitas berhasil diperbarui.',
        ]);
    }
    
    public function cleanNow()
    {
        $deletedCount = UserActivity::where('created_at', '<', now()->subDays($this->pruneDays))->delete();
        
        $this->showSettingsModal = false;
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Pembersihan Berhasil',
            'message' => number_format($deletedCount) . ' log aktivitas usang telah dihapus.',
        ]);
    }

    public function render()
    {
        $query = UserActivity::with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                })->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterType !== 'all') {
            $query->where('activity_type', $this->filterType);
        }

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $activities = $query->latest()->paginate($this->perPage);
        
        $activityTypes = UserActivity::select('activity_type')
            ->distinct()
            ->orderBy('activity_type')
            ->pluck('activity_type');

        return view('livewire.admin.user-activities', [
            'activities' => $activities,
            'activityTypes' => $activityTypes,
        ]);
    }
}
