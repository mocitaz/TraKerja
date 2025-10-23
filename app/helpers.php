<?php

if (!function_exists('is_premium')) {
    /**
     * Check if user is premium
     *
     * @param \App\Models\User|null $user
     * @return bool
     */
    function is_premium($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }
        
        return $user->isPremium();
    }
}

if (!function_exists('cv_templates_count')) {
    /**
     * Get CV templates count for current user
     *
     * @return int
     */
    function cv_templates_count(): int
    {
        $user = auth()->user();
        
        if (!$user) {
            return 1; // Default free tier
        }
        
        return $user->getCvTemplatesCount();
    }
}

if (!function_exists('format_date_range')) {
    /**
     * Format date range for display
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param bool $isCurrent
     * @return string
     */
    function format_date_range($startDate, $endDate = null, $isCurrent = false): string
    {
        if (!$startDate) {
            return '';
        }
        
        $start = \Carbon\Carbon::parse($startDate)->format('M Y');
        
        if ($isCurrent) {
            return $start . ' - Present';
        }
        
        if ($endDate) {
            $end = \Carbon\Carbon::parse($endDate)->format('M Y');
            return $start . ' - ' . $end;
        }
        
        return $start;
    }
}

if (!function_exists('user_has_feature')) {
    /**
     * Check if user has access to a feature
     *
     * @param string $feature
     * @param \App\Models\User|null $user
     * @return bool
     */
    function user_has_feature(string $feature, $user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }
        
        return $user->canAccessFeature($feature);
    }
}
