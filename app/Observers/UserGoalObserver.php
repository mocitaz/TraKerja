<?php

namespace App\Observers;

use App\Models\UserGoal;
use App\Services\ActivityLogger;

class UserGoalObserver
{
    /**
     * Handle the UserGoal "created" event.
     */
    public function created(UserGoal $userGoal): void
    {
        ActivityLogger::log(
            'goal_set',
            "User mengatur target lamaran baru: {$userGoal->target_applications} lamaran/minggu",
            'success',
            ['target' => $userGoal->target_applications],
            $userGoal->user_id
        );
    }

    /**
     * Handle the UserGoal "updated" event.
     */
    public function updated(UserGoal $userGoal): void
    {
        if ($userGoal->isDirty('target_applications')) {
            ActivityLogger::log(
                'goal_update',
                "User mengubah target lamaran menjadi {$userGoal->target_applications} lamaran/minggu",
                'success',
                [
                    'old' => $userGoal->getOriginal('target_applications'),
                    'new' => $userGoal->target_applications
                ],
                $userGoal->user_id
            );
        }
    }
}
