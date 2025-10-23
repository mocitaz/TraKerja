<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $fillable = [
        'key',
        'group',
        'type',
        'value',
        'description',
        'metadata',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Cache duration in seconds (1 hour)
     */
    const CACHE_DURATION = 3600;

    /**
     * Get setting value with proper type casting
     */
    public function getTypedValue()
    {
        return match($this->type) {
            'boolean' => (bool) $this->value,
            'number' => (int) $this->value,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Get setting by key with caching
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "app_setting_{$key}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($key, $default) {
            $setting = self::where('key', $key)
                ->where('is_active', true)
                ->first();
            
            if (!$setting) {
                return $default;
            }
            
            return $setting->getTypedValue();
        });
    }

    /**
     * Set setting value
     */
    public static function set(string $key, $value): bool
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return false;
        }

        $setting->value = $value;
        $setting->save();

        // Clear cache
        Cache::forget("app_setting_{$key}");
        
        return true;
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group): array
    {
        $cacheKey = "app_settings_group_{$group}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($group) {
            return self::where('group', $group)
                ->where('is_active', true)
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => $setting->getTypedValue()];
                })
                ->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        $settings = self::all();
        
        foreach ($settings as $setting) {
            Cache::forget("app_setting_{$setting->key}");
        }
        
        // Clear group caches
        $groups = self::distinct('group')->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("app_settings_group_{$group}");
        }
    }

    /**
     * Get premium price (convenience method)
     */
    public static function getPremiumPrice(): int
    {
        return (int) self::get('premium_price', 199000);
    }

    /**
     * Get discounted price if discount is active
     */
    public static function getDiscountedPrice(): int
    {
        $basePrice = self::getPremiumPrice();
        $discountPercentage = (int) self::get('discount_percentage', 0);
        $isDiscountActive = self::get('discount_code') !== '' && self::get('discount_percentage') > 0;

        if (!$isDiscountActive) {
            return $basePrice;
        }

        $discount = ($basePrice * $discountPercentage) / 100;
        return (int) ($basePrice - $discount);
    }

    /**
     * Check if feature is available for user
     */
    public static function isFeatureAvailable(string $featureKey, bool $isPremium): bool
    {
        $featureAccess = self::get($featureKey, 'free');
        
        return match($featureAccess) {
            'free' => true,
            'premium' => $isPremium,
            'disabled' => false,
            default => false,
        };
    }

    /**
     * Check if user has reached limit
     */
    public static function hasReachedLimit(string $limitKey, int $currentCount, bool $isPremium): bool
    {
        // Premium users have no limits
        if ($isPremium) {
            return false;
        }

        $limit = (int) self::get($limitKey, 0);
        
        // 0 means unlimited
        if ($limit === 0) {
            return false;
        }

        return $currentCount >= $limit;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when settings are updated
        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}
