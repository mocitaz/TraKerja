<?php

/**
 * Monetization Helper Functions
 * 
 * Global helper functions for checking monetization phase,
 * feature access, and user permissions.
 */

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

if (!function_exists('current_phase')) {
    /**
     * Get current monetization phase (1, 2, or 3)
     * 
     * @return int
     */
    function current_phase(): int
    {
        return Setting::getMonetizationPhase();
    }
}

if (!function_exists('is_phase')) {
    /**
     * Check if current phase matches the given phase
     * 
     * @param int $phase Phase number to check
     * @return bool
     */
    function is_phase(int $phase): bool
    {
        return current_phase() === $phase;
    }
}

if (!function_exists('can_access')) {
    /**
     * Check if user can access a specific feature
     * 
     * @param string $feature Feature name
     * @param \App\Models\User|null $user User instance (default: authenticated user)
     * @return bool
     */
    function can_access(string $feature, $user = null): bool
    {
        if (!$user) {
            $user = Auth::check() ? Auth::user() : null;
        }
        
        if (!$user) {
            return false;
        }
        
        return $user->canAccessFeature($feature);
    }
}

if (!function_exists('feature_limit')) {
    /**
     * Get feature limit for user
     * 
     * @param string $feature Feature name (e.g., "cv_exports", "cv_templates")
     * @param \App\Models\User|null $user User instance (default: authenticated user)
     * @return mixed "unlimited" or integer limit
     */
    function feature_limit(string $feature, $user = null)
    {
        if (!$user) {
            $user = Auth::check() ? Auth::user() : null;
        }
        
        if (!$user) {
            return 0;
        }
        
        return $user->getFeatureLimit($feature);
    }
}

if (!function_exists('is_premium')) {
    /**
     * Check if user is premium
     * 
     * @param \App\Models\User|null $user User instance (default: authenticated user)
     * @return bool
     */
    function is_premium($user = null): bool
    {
        $user = $user ?? Auth::user();
        
        if (!$user) {
            return false;
        }
        
        return $user->is_premium;
    }
}

if (!function_exists('premium_price')) {
    /**
     * Get current premium price (with discount if active)
     * For specific user, respects grandfathered discounts
     * 
     * @param \App\Models\User|null $user User instance (optional)
     * @return int Price in IDR
     */
    function premium_price($user = null): int
    {
        // If user provided, get their specific price (with grandfather discount)
        if ($user) {
            return $user->getPremiumPrice();
        }
        
        // If authenticated, get auth user's price
        if (Auth::check()) {
            return Auth::user()->getPremiumPrice();
        }
        
        // Otherwise get base price with promotional discount if any
        $basePrice = Setting::get('premium_price', 199000);
        $discountActive = Setting::get('premium_discount_active', false);
        
        if (!$discountActive) {
            return (int) $basePrice;
        }
        
        $discountPercent = Setting::get('premium_discount_percent', 0);
        return (int) ($basePrice * (1 - $discountPercent / 100));
    }
}

if (!function_exists('format_price')) {
    /**
     * Format price in Indonesian Rupiah format
     * 
     * @param int $price Price in IDR
     * @param bool $withPrefix Include "Rp" prefix
     * @return string Formatted price
     */
    function format_price(int $price, bool $withPrefix = true): string
    {
        $formatted = number_format($price, 0, ',', '.');
        return $withPrefix ? "Rp {$formatted}" : $formatted;
    }
}

if (!function_exists('has_grandfathered_benefit')) {
    /**
     * Check if user has specific grandfathered benefit
     * 
     * @param string $benefit Benefit identifier
     * @param \App\Models\User|null $user User instance (default: authenticated user)
     * @return bool
     */
    function has_grandfathered_benefit(string $benefit, $user = null): bool
    {
        if (!$user) {
            $user = Auth::check() ? Auth::user() : null;
        }
        
        if (!$user) {
            return false;
        }
        
        return $user->hasGrandfatheredBenefit($benefit);
    }
}

if (!function_exists('cv_templates_count')) {
    /**
     * Get number of CV templates available for user
     * 
     * @param \App\Models\User|null $user User instance (default: authenticated user)
     * @return int Number of templates (1, 3, or 5)
     */
    function cv_templates_count($user = null): int
    {
        if (!$user) {
            $user = Auth::check() ? Auth::user() : null;
        }
        
        if (!$user) {
            return 1; // Default free tier
        }
        
        return $user->getCvTemplatesCount();
    }
}

if (!function_exists('remaining_exports')) {
    /**
     * Get remaining CV exports for user this month
     * 
     * @param \App\Models\User|null $user User instance (default: authenticated user)
     * @return mixed "unlimited" or integer
     */
    function remaining_exports($user = null)
    {
        if (!$user) {
            $user = Auth::check() ? Auth::user() : null;
        }
        
        if (!$user) {
            return 0;
        }
        
        return $user->getRemainingExports();
    }
}

if (!function_exists('phase_name')) {
    /**
     * Get human-readable phase name
     * 
     * @param int|null $phase Phase number (default: current phase)
     * @return string
     */
    function phase_name(?int $phase = null): string
    {
        $phase = $phase ?? current_phase();
        
        return match($phase) {
            1 => 'Launch - Free All',
            2 => 'Soft Premium',
            3 => 'Full Premium',
            default => 'Unknown'
        };
    }
}

if (!function_exists('phase_emoji')) {
    /**
     * Get emoji for phase
     * 
     * @param int|null $phase Phase number (default: current phase)
     * @return string
     */
    function phase_emoji(?int $phase = null): string
    {
        $phase = $phase ?? current_phase();
        
        return match($phase) {
            1 => '🟢',
            2 => '🟡',
            3 => '🔵',
            default => '⚪'
        };
    }
}
