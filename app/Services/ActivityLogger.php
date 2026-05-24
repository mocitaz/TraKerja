<?php

namespace App\Services;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log a user activity.
     *
     * @param string $type The activity type (e.g., 'login', 'job_add')
     * @param string $description A human-readable description
     * @param string $status The status of the activity (success, failed, pending)
     * @param array $metadata Any additional data to store as JSON
     * @param int|null $userId Optional user ID, defaults to currently authenticated user
     * @return UserActivity|null
     */
    public static function log(string $type, string $description, string $status = 'success', array $metadata = [], ?int $userId = null)
    {
        $userId = $userId ?? Auth::id();

        // Optional: We can still log activities for guests if we want, but generally we want to track users.
        // If we want to strictly require a user_id:
        // if (!$userId) return null;

        try {
            return UserActivity::create([
                'user_id' => $userId,
                'activity_type' => $type,
                'description' => $description,
                'status' => $status,
                'metadata' => empty($metadata) ? null : $metadata,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to log user activity: " . $e->getMessage());
            return null;
        }
    }
}
