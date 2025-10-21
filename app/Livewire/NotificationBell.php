<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\NotificationService;

class NotificationBell extends Component
{
    public $notifications = [];
    public $showNotifications = false;

    protected $listeners = [
        'showNotification' => 'addActionNotification',
        'hideNotification' => 'removeNotification',
        'clearNotifications' => 'clearAllNotifications'
    ];

    public function mount()
    {
        // Always start with closed panel
        $this->showNotifications = false;
        
        // Load notifications from session to persist across page changes
        $this->notifications = session()->get('user_notifications', []);
        
        // Don't auto-check system notifications on mount
        // Only check when explicitly requested or on first visit
        $firstVisitKey = 'first_visit_' . now()->format('Y-m-d');
        if (!session()->has($firstVisitKey)) {
            $this->checkSystemNotifications();
            session()->put($firstVisitKey, true);
        }
    }

    public function checkSystemNotifications()
    {
        // Get dismissed notifications for today
        $dismissedKey = 'dismissed_notifications_' . now()->format('Y-m-d');
        $dismissed = session()->get($dismissedKey, []);
        
        // Get existing notification IDs to avoid duplicates
        $existingIds = array_column($this->notifications, 'id');
        
        // Check goal progress
        $goalNotification = NotificationService::checkGoalProgress();
        if ($goalNotification && !in_array('goal_progress', $dismissed) && !in_array('goal_progress', $existingIds)) {
            $this->addNotification(array_merge($goalNotification, ['id' => 'goal_progress']));
        }

        // Check new applications
        $appNotification = NotificationService::checkNewApplications();
        if ($appNotification && !in_array('new_applications', $dismissed) && !in_array('new_applications', $existingIds)) {
            $this->addNotification(array_merge($appNotification, ['id' => 'new_applications']));
        }

        // Check interview reminders
        $interviewNotification = NotificationService::checkInterviewReminders();
        if ($interviewNotification && !in_array('interview_reminders', $dismissed) && !in_array('interview_reminders', $existingIds)) {
            $this->addNotification(array_merge($interviewNotification, ['id' => 'interview_reminders']));
        }
    }

    public function addNotification($data)
    {
        $notification = array_merge($data, [
            'id' => $data['id'] ?? uniqid(),
            'timestamp' => now()
        ]);
        
        $this->notifications[] = $notification;
        
        // Save to session to persist across page changes
        session()->put('user_notifications', $this->notifications);
    }

    public function addActionNotification($data)
    {
        $this->addNotification($data);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_filter($this->notifications, function($notification) use ($id) {
            return $notification['id'] !== $id;
        });
        
        // Save to session to persist across page changes
        session()->put('user_notifications', $this->notifications);
    }

    public function clearAllNotifications()
    {
        $this->notifications = [];
        
        // Save to session to persist across page changes
        session()->put('user_notifications', $this->notifications);
    }

    public function toggleNotifications()
    {
        $this->showNotifications = !$this->showNotifications;
    }

    public function closePanel()
    {
        $this->showNotifications = false;
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
