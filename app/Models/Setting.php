<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'category', 'description'];
    
    /**
     * Get setting value with caching (1 hour)
     * 
     * @param string $key Setting key
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function() use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            return self::castValue($setting->value, $setting->type);
        });
    }
    
    /**
     * Set setting value and clear cache
     * 
     * @param string $key Setting key
     * @param mixed $value Setting value
     * @return Setting
     */
    public static function set($key, $value)
    {
        // Convert arrays/objects to JSON
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
            $type = 'json';
        } elseif (is_bool($value)) {
            $value = $value ? 'true' : 'false';
            $type = 'boolean';
        } elseif (is_numeric($value)) {
            $type = 'integer';
        } else {
            $type = 'string';
        }
        
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => (string)$value, 'type' => $type]
        );
        
        Cache::forget("setting_{$key}");
        
        return $setting;
    }
    
    /**
     * Cast value based on type
     * 
     * @param string $value
     * @param string $type
     * @return mixed
     */
    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
    
    /**
     * Check if monetization is enabled
     * 
     * @return bool
     */
    public static function isMonetizationEnabled()
    {
        return (bool) self::get('monetization_enabled', false);
    }
    
    /**
     * Get current monetization phase (1, 2, or 3) - DEPRECATED, kept for backward compatibility
     * Use isMonetizationEnabled() instead
     * 
     * @return int
     */
    public static function getMonetizationPhase()
    {
        // Convert new boolean system to old phase system for backward compatibility
        return self::isMonetizationEnabled() ? 2 : 1;
    }
    
    /**
     * Check if feature is accessible for user
     * 
     * @param string $feature Feature name
     * @param \App\Models\User|null $user User instance
     * @return bool
     */
    public static function canAccess($feature, $user = null)
    {
        // If monetization is OFF, everything is FREE
        if (!self::isMonetizationEnabled()) {
            return true;
        }
        
        // If monetization is ON, check if user is premium
        if ($user && $user->is_premium && $user->payment_status === \App\Models\User::PAYMENT_STATUS_PAID) {
            return true;
        }
        
        // Free users get limited access when monetization is enabled
        // Define which features are still available for free
        $freeFeatures = [
            'job_tracker', 
            'cv_builder_basic',
            'interview_calendar'
        ];
        
        return in_array($feature, $freeFeatures);
    }
    
    /**
     * Get feature limit for user (e.g., "unlimited", 3, etc.)
     * 
     * @param string $feature Feature name (e.g., "cv_exports", "cv_templates")
     * @param \App\Models\User|null $user User instance
     * @return mixed "unlimited" or integer limit
     */
    public static function getLimit($feature, $user = null)
    {
        // If monetization is OFF, everything is unlimited
        if (!self::isMonetizationEnabled()) {
            return 'unlimited';
        }
        
        // Premium users get unlimited
        if ($user && $user->is_premium && $user->payment_status === \App\Models\User::PAYMENT_STATUS_PAID) {
            return 'unlimited';
        }
        
        // Free users get limits when monetization is enabled
        $freeLimits = [
            'cv_templates' => 1,
            'cv_exports' => 'unlimited', // FREE users can export unlimited times
            'job_applications' => 30,
            'goals' => 5
        ];
        
        return $freeLimits[$feature] ?? 'unlimited';
    }
    
    /**
     * Get all settings by category
     * 
     * @param string $category
     * @return \Illuminate\Support\Collection
     */
    public static function getByCategory($category)
    {
        return self::where('category', $category)->get()->map(function($setting) {
            return [
                'key' => $setting->key,
                'value' => self::castValue($setting->value, $setting->type),
                'type' => $setting->type,
                'description' => $setting->description
            ];
        });
    }
    
    /**
     * Clear all settings cache
     * 
     * @return void
     */
    public static function clearCache()
    {
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget("setting_{$setting->key}");
        }
    }
}
