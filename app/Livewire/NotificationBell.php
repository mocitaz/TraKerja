<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use App\Services\NotificationService;

class NotificationBell extends Component
{
    public $notifications = [];
    public $showNotifications = false;

    protected $listeners = [
        'showNotification' => 'handleShowNotification',
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
        
        // Only check important notifications to reduce spam
        // Check goal progress (only achievements, not reminders)
        $goalNotification = NotificationService::checkGoalProgress();
        if ($goalNotification && !in_array('goal_progress', $dismissed) && !in_array('goal_progress', $existingIds)) {
            // Pastikan message ada
            if (empty($goalNotification['message']) && !empty($goalNotification['title'])) {
                $goalNotification['message'] = $goalNotification['title'];
            }
            // Skip jika tidak ada message
            if (!empty($goalNotification['message'])) {
                // Only show if it's an achievement (success type)
                if ($goalNotification['type'] === 'success') {
                    $notificationData = array_merge($goalNotification, ['id' => 'goal_progress', 'important' => true]);
                    $this->addNotification($notificationData);
                    $this->dispatch('showToast', $notificationData);
                }
            }
        }

        // Skip new applications notification (too spammy)
        // Check interview reminders (only if urgent - within 24 hours)
        $interviewNotification = NotificationService::checkInterviewReminders();
        if ($interviewNotification && !in_array('interview_reminders', $dismissed) && !in_array('interview_reminders', $existingIds)) {
            // Pastikan message ada
            if (empty($interviewNotification['message']) && !empty($interviewNotification['title'])) {
                $interviewNotification['message'] = $interviewNotification['title'];
            }
            // Skip jika tidak ada message
            if (!empty($interviewNotification['message'])) {
                $notificationData = array_merge($interviewNotification, ['id' => 'interview_reminders', 'important' => true]);
                $this->addNotification($notificationData);
                $this->dispatch('showToast', $notificationData);
            }
        }
    }

    public function addNotification($data)
    {
        // Pastikan message ada
        if (empty($data['message']) && !empty($data['title'])) {
            $data['message'] = $data['title'];
        }
        
        // Skip jika tidak ada message
        if (empty($data['message'])) {
            return;
        }
        
        $notificationId = $data['id'] ?? uniqid();
        
        // Cek duplikasi berdasarkan ID
        $existingIds = array_column($this->notifications, 'id');
        if (in_array($notificationId, $existingIds)) {
            // Skip jika sudah ada notifikasi dengan ID yang sama
            return;
        }
        
        $notification = array_merge($data, [
            'id' => $notificationId,
            'timestamp' => now()
        ]);
        
        $this->notifications[] = $notification;
        
        // Save to session to persist across page changes
        session()->put('user_notifications', $this->notifications);
    }

    public function handleShowNotification(...$args)
    {
        // Handle different parameter formats from Livewire events
        if (count($args) === 1 && is_array($args[0])) {
            // Array format: ['type' => 'success', 'title' => '...', ...]
            $notificationData = array_merge($args[0], [
                'id' => $args[0]['id'] ?? uniqid(),
                'timestamp' => now()
            ]);
        } elseif (count($args) === 4) {
            // Named parameters format: type, title, message, duration
            $notificationData = [
                'id' => uniqid(),
                'type' => $args[0],
                'title' => $args[1],
                'message' => $args[2],
                'duration' => $args[3],
                'timestamp' => now()
            ];
        } else {
            // Skip default notification - harus ada message yang jelas
            return;
        }
        
        // Pastikan message selalu ada, jika tidak ada gunakan title
        if (empty($notificationData['message'])) {
            if (!empty($notificationData['title'])) {
                $notificationData['message'] = $notificationData['title'];
            } else {
                // Jika tidak ada message dan title, skip notifikasi ini
                return;
            }
        }
        
        // Cek duplikasi berdasarkan ID
        $existingIds = array_column($this->notifications, 'id');
        if (in_array($notificationData['id'], $existingIds)) {
            // Skip jika sudah ada notifikasi dengan ID yang sama
            return;
        }
        
        // Filter: Only show important notifications (success, error, warning, interview, goals)
        // Skip regular info notifications to reduce spam
        $importantTypes = ['success', 'error', 'warning'];
        $importantKeywords = ['interview', 'goal', 'achieved', 'milestone', 'reminder', 'accepted', 'declined'];
        
        $isImportant = in_array($notificationData['type'], $importantTypes);
        $hasImportantKeyword = false;
        
        if (!$isImportant) {
            $titleLower = strtolower($notificationData['title'] ?? '');
            $messageLower = strtolower($notificationData['message'] ?? '');
            foreach ($importantKeywords as $keyword) {
                if (str_contains($titleLower, $keyword) || str_contains($messageLower, $keyword)) {
                    $hasImportantKeyword = true;
                    break;
                }
            }
        }
        
        // Only add if it's important or explicitly marked as important
        if ($isImportant || $hasImportantKeyword || ($notificationData['important'] ?? false)) {
            $this->addNotification($notificationData);
            
            // Dispatch to show toast notification
            $this->dispatch('showToast', $notificationData);
        }
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
