<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\UserGoal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class NotificationService
{
    public static function checkGoalProgress()
    {
        // Don't show goal notifications for admin users
        $user = Auth::user();
        if ($user && ($user->is_admin || $user->role === 'admin')) {
            return null;
        }

        // Check if we already showed goal notifications today
        $sessionKey = 'goal_notifications_' . Carbon::today()->format('Y-m-d');
        if (Session::has($sessionKey)) {
            return null;
        }

        $userId = Auth::id();
        $currentGoal = UserGoal::getCurrentWeekGoal($userId);
        
        if (!$currentGoal) {
            // Only show this once per day
            Session::put($sessionKey, true);
            return [
                'type' => 'info',
                'title' => 'Set Your Weekly Goals',
                'message' => 'Visit Goals & Cadence page to set your weekly targets and start tracking!',
                'duration' => 5000
            ];
        }

        $startOfWeek = $currentGoal->start_date;
        $endOfWeek = $currentGoal->end_date;
        
        $actualApplied = JobApplication::where('user_id', $userId)
            ->whereBetween('application_date', [$startOfWeek, $endOfWeek])
            ->count();

        $progress = ($actualApplied / $currentGoal->target_applied_weekly) * 100;

        if ($progress >= 100) {
            // Only show achievement notification once per day
            Session::put($sessionKey, true);
            return [
                'type' => 'success',
                'title' => 'Weekly Target Achieved!',
                'message' => "Great job! You've applied to {$actualApplied} jobs this week.",
                'duration' => 5000
            ];
        } elseif ($progress >= 80) {
            // Only show progress notification once per day
            Session::put($sessionKey, true);
            return [
                'type' => 'warning',
                'title' => 'Almost There!',
                'message' => "You're {$progress}% to your weekly target. Keep going!",
                'duration' => 3000
            ];
        } elseif ($progress < 50 && Carbon::now()->isWeekend()) {
            // Only show weekend reminder once per day
            Session::put($sessionKey, true);
            return [
                'type' => 'info',
                'title' => 'Weekend Reminder',
                'message' => "You're {$progress}% to your weekly target. Consider applying to more jobs this weekend!",
                'duration' => 4000
            ];
        }

        return null;
    }

    public static function checkNewApplications()
    {
        // Disable this notification to prevent spam
        // Users will get specific notifications when they add jobs
        return null;
    }

    public static function checkInterviewReminders()
    {
        // Don't show interview reminders for admin users
        $user = Auth::user();
        if ($user && ($user->is_admin || $user->role === 'admin')) {
            return null;
        }

        // Check if we already showed interview reminders today
        $sessionKey = 'interview_reminders_' . Carbon::today()->format('Y-m-d');
        if (Session::has($sessionKey)) {
            return null;
        }

        $userId = Auth::id();
        $upcomingInterviews = JobApplication::where('user_id', $userId)
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->where('application_status', 'On Process')
            ->count();

        if ($upcomingInterviews > 0) {
            // Only show once per day
            Session::put($sessionKey, true);
            return [
                'type' => 'info',
                'title' => 'Interview Preparation',
                'message' => "You have {$upcomingInterviews} interview(s) coming up. Time to prepare!",
                'duration' => 4000
            ];
        }

        return null;
    }

    public static function notifyJobAdded($jobId, $companyName)
    {
        return [
            'type' => 'success',
            'title' => 'Job Application Added',
            'message' => "Successfully added application for {$companyName}",
            'duration' => 3000
        ];
    }

    public static function notifyJobUpdated($jobId, $companyName)
    {
        return [
            'type' => 'info',
            'title' => 'Job Application Updated',
            'message' => "Successfully updated application for {$companyName}",
            'duration' => 3000
        ];
    }

    public static function notifyJobDeleted($companyName)
    {
        return [
            'type' => 'warning',
            'title' => 'Job Application Deleted',
            'message' => "Successfully deleted application for {$companyName}",
            'duration' => 3000
        ];
    }

    public static function notifyStatusUpdated($companyName, $newStatus)
    {
        return [
            'type' => 'info',
            'title' => 'Status Updated',
            'message' => "Application for {$companyName} updated to {$newStatus}",
            'duration' => 3000
        ];
    }
}
