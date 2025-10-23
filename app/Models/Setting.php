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
     * Get current monetization phase (1, 2, or 3)
     * 
     * @return int
     */
    public static function getMonetizationPhase()
    {
        return (int) self::get('monetization_phase', 1);
    }
    
    /**
     * Check if feature is accessible for user based on current phase
     * 
     * @param string $feature Feature name
     * @param \App\Models\User|null $user User instance
     * @return bool
     */
    public static function canAccess($feature, $user = null)
    {
        $phase = self::getMonetizationPhase();
        $featureAccess = self::get('feature_access', []);
        $phaseKey = "phase_{$phase}";
        
        // Phase 1: Everything is free
        if ($phase == 1) {
            return true;
        }
        
        // Get feature config for current phase
        $phaseConfig = $featureAccess[$phaseKey] ?? [];
        $featureValue = $phaseConfig[$feature] ?? 'free';
        
        // If feature is free, allow
        if ($featureValue === 'free' || $featureValue === true) {
            return true;
        }
        
        // If feature is premium, check user status
        if ($featureValue === 'premium' && $user) {
            // Check grandfathered benefits first
            if ($user->hasGrandfatheredBenefit($feature)) {
                return true;
            }
            return $user->is_premium;
        }
        
        // Default: free access
        return true;
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
        $phase = self::getMonetizationPhase();
        $featureAccess = self::get('feature_access', []);
        $phaseKey = "phase_{$phase}";
        $phaseConfig = $featureAccess[$phaseKey] ?? [];
        
        // Phase 1: Unlimited everything
        if ($phase == 1) {
            return 'unlimited';
        }
        
        // Premium users get unlimited
        if ($user && $user->is_premium) {
            $premiumKey = $feature . '_premium';
            return $phaseConfig[$premiumKey] ?? 'unlimited';
        }
        
        // Check for grandfathered benefits
        if ($user && $feature === 'cv_templates') {
            $grandfatheredCount = $user->getCvTemplatesCount();
            if ($grandfatheredCount > 1) {
                return $grandfatheredCount;
            }
        }
        
        // Free users get limited
        $freeKey = $feature . '_free';
        return $phaseConfig[$freeKey] ?? 'unlimited';
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
