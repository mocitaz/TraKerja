<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserGoal;
use App\Models\JobApplication;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GoalsCadenceManager extends Component
{
    // Form properties
    public $targetAppliedWeekly = 5;
    public $targetFollowupWeekly = 3;
    public $startDate;
    public $endDate;
    public $goalPeriod = 'weekly'; // 'weekly' or 'monthly'
    public $showGoalModal = false;

    // Computed properties will be calculated in getters
    protected $listeners = ['goalUpdated' => '$refresh'];

    public function mount()
    {
        // Set default dates based on period
        $this->updateDateRange();
        
        // Clean up old notification sessions (older than 7 days)
        $this->cleanupOldNotificationSessions();
        
        // Load current goal if exists
        $userId = Auth::id();
        if ($userId) {
            $currentGoal = UserGoal::getCurrentWeekGoal($userId);
            if ($currentGoal) {
                $this->targetAppliedWeekly = $currentGoal->target_applied_weekly;
                $this->targetFollowupWeekly = $currentGoal->target_followup_weekly;
                $this->startDate = $currentGoal->start_date->format('Y-m-d');
                $this->endDate = $currentGoal->end_date->format('Y-m-d');
            }

            // Only send welcome notification if no goals are set and not already shown today
            if (!$currentGoal) {
                $welcomeNotificationKey = 'goals_welcome_notification_' . now()->format('Y-m-d');
                if (!session()->has($welcomeNotificationKey)) {
                    $this->dispatch('showNotification', [
                        'type' => 'info',
                        'title' => 'Welcome to Goals & Cadence',
                        'message' => 'Set your weekly targets to track your progress!',
                        'duration' => 4000
                    ]);
                    session()->put($welcomeNotificationKey, true);
                }
            }
        }
    }
    
    private function cleanupOldNotificationSessions()
    {
        $sevenDaysAgo = now()->subDays(7)->format('Y-m-d');
        $sessionKeys = [
            'goals_welcome_notification_',
            'target_achieved_',
            'almost_there_'
        ];
        
        foreach ($sessionKeys as $keyPrefix) {
            // Remove sessions older than 7 days
            for ($i = 0; $i < 7; $i++) {
                $date = now()->subDays($i)->format('Y-m-d');
                $sessionKey = $keyPrefix . $date;
                if (session()->has($sessionKey) && $date < $sevenDaysAgo) {
                    session()->forget($sessionKey);
                }
            }
        }
    }

    public function updateDateRange()
    {
        if ($this->goalPeriod === 'monthly') {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
            $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        }
    }

    public function updatedGoalPeriod()
    {
        $this->updateDateRange();
    }

    public function openGoalModal()
    {
        $this->showGoalModal = true;
    }

    public function closeGoalModal()
    {
        $this->showGoalModal = false;
    }

    /**
     * Set weekly goals method
     */
    public function setWeeklyGoals()
    {
        $this->validate([
            'targetAppliedWeekly' => 'required|integer|min:1',
            'targetFollowupWeekly' => 'required|integer|min:0',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
        ], [
            'targetAppliedWeekly.required' => 'Weekly application target is required',
            'targetAppliedWeekly.min' => 'Minimum application target is 1',
            'targetFollowupWeekly.required' => 'Weekly follow-up target is required',
            'targetFollowupWeekly.min' => 'Minimum follow-up target is 0',
            'startDate.required' => 'Start date is required',
            'endDate.required' => 'End date is required',
            'endDate.after' => 'End date must be after start date',
        ]);

        $userId = Auth::id();
        
        // Check if there's already a goal for this week
        $existingGoal = UserGoal::where('user_id', $userId)
            ->where('start_date', $this->startDate)
            ->where('end_date', $this->endDate)
            ->first();

        if ($existingGoal) {
            // Update existing goal
            $existingGoal->update([
                'target_applied_weekly' => $this->targetAppliedWeekly,
                'target_followup_weekly' => $this->targetFollowupWeekly,
            ]);
            $message = 'Weekly goals updated successfully!';
        } else {
            // Create new goal
            UserGoal::create([
                'user_id' => $userId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'target_applied_weekly' => $this->targetAppliedWeekly,
                'target_followup_weekly' => $this->targetFollowupWeekly,
                'is_achieved' => false,
            ]);
            $message = 'Weekly goals saved successfully!';
        }

        session()->flash('message', $message);
        $this->dispatch('goalUpdated');
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Goals Updated',
            'message' => $message
        ]);
        
        // Close modal after successful save
        $this->closeGoalModal();
    }

    /**
     * Get current week's actual applied count
     */
    public function getActualAppliedProperty()
    {
        $userId = Auth::id();
        if (!$userId) return 0;
        
        $startOfWeek = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now()->startOfWeek();
        $endOfWeek = $this->endDate ? Carbon::parse($this->endDate) : Carbon::now()->endOfWeek();
        
        return JobApplication::where('user_id', $userId)
            ->whereBetween('application_date', [$startOfWeek, $endOfWeek])
            ->count();
    }

    /**
     * Get current week's actual interviews count
     */
    public function getActualInterviewsProperty()
    {
        $userId = Auth::id();
        if (!$userId) return 0;
        
        $startOfWeek = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now()->startOfWeek();
        $endOfWeek = $this->endDate ? Carbon::parse($this->endDate) : Carbon::now()->endOfWeek();
        
        return JobApplication::where('user_id', $userId)
            ->whereBetween('application_date', [$startOfWeek, $endOfWeek])
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->count();
    }

    /**
     * Get follow-up count (simplified - count applications with follow-up stage)
     */
    public function getFollowUpCountProperty()
    {
        $userId = Auth::id();
        if (!$userId) return 0;
        
        $startOfWeek = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now()->startOfWeek();
        $endOfWeek = $this->endDate ? Carbon::parse($this->endDate) : Carbon::now()->endOfWeek();
        
        return JobApplication::where('user_id', $userId)
            ->whereBetween('application_date', [$startOfWeek, $endOfWeek])
            ->where('recruitment_stage', 'Follow Up')
            ->count();
    }

    /**
     * Get applied progress percentage
     */
    public function getAppliedProgressProperty()
    {
        if ($this->targetAppliedWeekly == 0) return 0;
        $progress = min(100, round(($this->actualApplied / $this->targetAppliedWeekly) * 100, 1));
        
        // Check for milestone notifications (only once per day)
        $this->checkMilestoneNotifications($progress);
        
        return $progress;
    }
    
    private function checkMilestoneNotifications($progress)
    {
        $today = now()->format('Y-m-d');
        $targetAchievedKey = "target_achieved_{$today}";
        $almostThereKey = "almost_there_{$today}";
        
        // Send notification for milestones (only once per day)
        if ($progress >= 100 && $this->actualApplied > 0 && !session()->has($targetAchievedKey)) {
            $this->dispatch('showNotification', [
                'type' => 'success',
                'title' => '🎯 Target Achieved!',
                'message' => "You've reached your weekly application target of {$this->targetAppliedWeekly} applications!",
                'duration' => 5000
            ]);
            session()->put($targetAchievedKey, true);
        } elseif ($progress >= 80 && $progress < 100 && !session()->has($almostThereKey)) {
            $this->dispatch('showNotification', [
                'type' => 'warning',
                'title' => '🔥 Almost There!',
                'message' => "You're {$progress}% to your weekly target. Keep going!",
                'duration' => 3000
            ]);
            session()->put($almostThereKey, true);
        }
    }

    /**
     * Get follow-up progress percentage
     */
    public function getFollowupProgressProperty()
    {
        if ($this->targetFollowupWeekly == 0) return 0;
        return min(100, round(($this->followUpCount / $this->targetFollowupWeekly) * 100, 1));
    }

    /**
     * Get current streak (consecutive days meeting daily target)
     */
    public function getCurrentStreakProperty()
    {
        $userId = Auth::id();
        if (!$userId) return 0;
        
        $dailyTarget = max(1, ceil($this->targetAppliedWeekly / 5)); // Minimum 1 application per day
        $streak = 0;
        $currentDate = Carbon::now();
        
        // Check backwards from today
        for ($i = 0; $i < 30; $i++) { // Check up to 30 days back
            $checkDate = $currentDate->copy()->subDays($i);
            
            $dailyCount = JobApplication::where('user_id', $userId)
                ->whereDate('application_date', $checkDate)
                ->count();
            
            if ($dailyCount >= $dailyTarget) {
                $streak++;
            } else {
                break; // Streak broken
            }
        }
        
        return $streak;
    }

    /**
     * Get cadence effect insights
     */
    public function getCadenceEffectProperty()
    {
        $userId = Auth::id();
        if (!$userId) {
            return [
                'target_met_rate' => 0,
                'target_missed_rate' => 0,
                'difference' => 0,
                'insufficient_data' => true
            ];
        }
        
        // Get goals from last 8 weeks
        $goals = UserGoal::getWeeklyHistory($userId, 8);
        
        if ($goals->count() < 2) {
            return [
                'target_met_rate' => 0,
                'target_missed_rate' => 0,
                'difference' => 0,
                'insufficient_data' => true
            ];
        }

        $targetMetPeriods = [];
        $targetMissedPeriods = [];

        foreach ($goals as $goal) {
            $actualApplied = JobApplication::where('user_id', $userId)
                ->whereBetween('application_date', [$goal->start_date, $goal->end_date])
                ->count();

            $actualInterviews = JobApplication::where('user_id', $userId)
                ->whereBetween('application_date', [$goal->start_date, $goal->end_date])
                ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
                ->count();

            if ($actualApplied >= $goal->target_applied_weekly) {
                $targetMetPeriods[] = $actualInterviews > 0 ? ($actualInterviews / $actualApplied) * 100 : 0;
            } else {
                $targetMissedPeriods[] = $actualInterviews > 0 ? ($actualInterviews / $actualApplied) * 100 : 0;
            }
        }

        $targetMetRate = count($targetMetPeriods) > 0 ? array_sum($targetMetPeriods) / count($targetMetPeriods) : 0;
        $targetMissedRate = count($targetMissedPeriods) > 0 ? array_sum($targetMissedPeriods) / count($targetMissedPeriods) : 0;

        return [
            'target_met_rate' => round($targetMetRate, 1),
            'target_missed_rate' => round($targetMissedRate, 1),
            'difference' => round($targetMetRate - $targetMissedRate, 1),
            'insufficient_data' => false
        ];
    }

    /**
     * Get weekly history for chart
     */
    public function getWeeklyHistoryProperty()
    {
        $userId = Auth::id();
        if (!$userId) return collect();
        
        return UserGoal::getWeeklyHistory($userId, 8);
    }

    /**
     * Check if goals are ending soon and target not met
     */
    public function getGoalsEndingSoonProperty()
    {
        $userId = Auth::id();
        if (!$userId) return false;
        
        $currentGoal = UserGoal::getCurrentWeekGoal($userId);
        if (!$currentGoal) {
            return false;
        }

        $now = Carbon::now();
        $endDate = Carbon::parse($currentGoal->end_date);
        $daysLeft = $now->diffInDays($endDate, false);
        
        // Check if less than 2 days left
        if ($daysLeft <= 2 && $daysLeft >= 0) {
            $actualApplied = $this->actualApplied;
            $targetApplied = $currentGoal->target_applied_weekly;
            $progress = $targetApplied > 0 ? ($actualApplied / $targetApplied) * 100 : 0;
            
            // Return true if less than 80% progress
            return $progress < 80;
        }
        
        return false;
    }

    /**
     * Get days left in current goal period
     */
    public function getDaysLeftProperty()
    {
        $userId = Auth::id();
        if (!$userId) return null;
        
        $currentGoal = UserGoal::getCurrentWeekGoal($userId);
        if (!$currentGoal) {
            return null;
        }

        $now = Carbon::now();
        $endDate = Carbon::parse($currentGoal->end_date);
        $daysLeft = $now->diffInDays($endDate, false);
        
        return max(0, $daysLeft);
    }

    /**
     * Check if user has any goals set
     */
    public function getHasGoalsProperty()
    {
        $userId = Auth::id();
        if (!$userId) return false;
        
        return UserGoal::getCurrentWeekGoal($userId) !== null;
    }

    public function render()
    {
        return view('livewire.goals-cadence-manager');
    }
}
