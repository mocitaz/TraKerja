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
    public ?int $selectedActivityId = null;

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

    public function resetFilters()
    {
        $this->reset('search', 'filterType', 'filterStatus');
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function showActivity(int $id)
    {
        UserActivity::findOrFail($id);
        $this->selectedActivityId = $id;
    }

    public function closeActivity()
    {
        $this->selectedActivityId = null;
    }

    public function loadSettings()
    {
        $setting = Setting::where('key', 'activity_log_prune_days')->first();
        $this->pruneDays = $setting ? (int) $setting->value : 90;
    }

    public function saveSettings()
    {
        $this->validate([
            'pruneDays' => 'required|integer|min:1|max:3650',
        ]);

        Setting::updateOrCreate(
            ['key' => 'activity_log_prune_days'],
            ['value' => (string) $this->pruneDays]
        );

        $this->showSettingsModal = false;

        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => __('Settings Saved'),
            'message' => __('Activity log retention period updated successfully.'),
        ]);
    }

    public function cleanNow()
    {
        $this->validate(['pruneDays' => 'required|integer|min:1|max:3650']);
        $deletedCount = UserActivity::where('created_at', '<', now()->subDays($this->pruneDays))->delete();

        $this->showSettingsModal = false;

        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => __('Cleanup Successful'),
            'message' => number_format($deletedCount) . ' obsolete activity logs (>' . $this->pruneDays . ' days) deleted.',
        ]);
    }

    public function cleanAll()
    {
        $deletedCount = UserActivity::count();
        UserActivity::truncate();

        $this->showSettingsModal = false;

        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => __('All Logs Purged'),
            'message' => number_format($deletedCount) . ' activity logs permanently deleted.',
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

        $activities = $query->latest()->orderByDesc('id')->paginate(in_array((int) $this->perPage, [15, 30, 50, 100], true) ? (int) $this->perPage : 15)->onEachSide(1);

        $rawTypes = UserActivity::select('activity_type')
            ->distinct()
            ->orderBy('activity_type')
            ->pluck('activity_type')
            ->toArray();

        $categories = [
            'Accounts & authentication' => ['login', 'logout', 'register', 'profile_update', 'password_change', 'account_delete'],
            'Applications' => ['job_add', 'job_edit', 'job_delete', 'interview_schedule', 'csv_export'],
            'CV Builder' => ['cv_data_update', 'cv_export'],
            'AI workspace' => ['ai_analyzer', 'ai_analyzer_usage', 'cover_letter', 'ai_photo'],
            'Weekly goals' => ['goal_set', 'goal_update'],
            'Chrome Extension' => ['extension_login', 'extension_job_save'],
            'Payments & membership' => ['top_up', 'premium_upgrade'],
            'Support & feedback' => ['support_ticket', 'feedback_submit'],
        ];

        $groupedTypes = [];
        $uncategorized = [];

        foreach ($rawTypes as $type) {
            $found = false;
            foreach ($categories as $cat => $types) {
                if (in_array($type, $types)) {
                    $groupedTypes[$cat][] = $type;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $uncategorized[] = $type;
            }
        }

        if (!empty($uncategorized)) {
            $groupedTypes['Other events'] = $uncategorized;
        }

        return view('livewire.admin.user-activities', [
            'activities' => $activities,
            'groupedTypes' => $groupedTypes,
            'selectedActivity' => $this->selectedActivityId ? UserActivity::with('user')->find($this->selectedActivityId) : null,
        ]);
    }
}
