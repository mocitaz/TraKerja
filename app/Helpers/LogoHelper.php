<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class LogoHelper
{
    /**
     * Get logo URL for user
     */
    public static function getLogoUrl($user)
    {
        if ($user && $user->logo) {
            return Storage::url($user->logo);
        }
        
        return null;
    }

    /**
     * Get default logo URL
     */
    public static function getDefaultLogoUrl()
    {
        return asset('images/default-logo.png');
    }

    /**
     * Get logo URL with fallback to default
     */
    public static function getLogoUrlWithFallback($user)
    {
        return self::getLogoUrl($user) ?? self::getDefaultLogoUrl();
    }
}
