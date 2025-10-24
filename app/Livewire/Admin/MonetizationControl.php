<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\User;

class MonetizationControl extends Component
{
    public $monetizationEnabled;
    public $showConfirmation = false;
    public $actionType; // 'enable' or 'disable'
    
    // Premium pricing
    public $premiumPrice;
    
    // Stats
    public $totalUsers = 0;
    public $freeUsers = 0;
    public $premiumUsers = 0;
    
    public function mount()
    {
        $this->monetizationEnabled = Setting::get('monetization_enabled', false);
        $this->premiumPrice = Setting::get('premium_price', 199000);
        $this->loadStats();
    }
    
    public function loadStats()
    {
        $this->totalUsers = User::where('role', '!=', 'admin')->count();
        $this->freeUsers = User::where('role', '!=', 'admin')->where('is_premium', false)->count();
        $this->premiumUsers = User::where('role', '!=', 'admin')->where('is_premium', true)->count();
    }
    
    public function toggleMonetization($enable)
    {
        // Don't confirm if same state
        if ($enable == $this->monetizationEnabled) {
            return;
        }
        
        $this->actionType = $enable ? 'enable' : 'disable';
        $this->showConfirmation = true;
    }
    
    public function confirmToggle()
    {
        $newState = $this->actionType === 'enable';
        $oldState = $this->monetizationEnabled;
        
        // Update monetization state
        Setting::set('monetization_enabled', $newState);
        $this->monetizationEnabled = $newState;
        $this->showConfirmation = false;
        
        // Clear settings cache
        Setting::clearCache();
        
        // Log change
        Log::info("Monetization " . ($newState ? 'ENABLED' : 'DISABLED'), [
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name,
            'timestamp' => now(),
            'total_users_affected' => $this->totalUsers,
            'previous_state' => $oldState ? 'enabled' : 'disabled'
        ]);
        
        $message = $newState 
            ? "Monetization ACTIVATED! Premium features now require payment."
            : "Monetization DEACTIVATED! All features are now FREE for everyone.";
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => $newState ? '💎 Monetization Enabled' : '🎁 Monetization Disabled',
            'message' => $message
        ]);
        
        // Reload stats
        $this->loadStats();
    }
    
    public function cancelToggle()
    {
        $this->actionType = null;
        $this->showConfirmation = false;
    }
    
    public function updatePremiumPrice()
    {
        $this->validate([
            'premiumPrice' => 'required|numeric|min:0'
        ]);
        
        Setting::set('premium_price', $this->premiumPrice);
        Setting::clearCache();
        
        Log::info("Premium price updated to Rp " . number_format($this->premiumPrice), [
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name,
            'timestamp' => now()
        ]);
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Price Updated!',
            'message' => 'Premium price has been updated to Rp ' . number_format($this->premiumPrice)
        ]);
    }
    
    public function render()
    {
        // Get feature matrix
        $featureMatrix = $this->buildFeatureMatrix();
        
        return view('livewire.admin.monetization-control', [
            'featureMatrix' => $featureMatrix
        ]);
    }
    
    private function buildFeatureMatrix()
    {
        // Define feature access based on monetization state
        if (!$this->monetizationEnabled) {
            // All features FREE when monetization is OFF
            return [
                'Job Application Tracker' => [
                    'free' => '✅ Unlimited',
                    'premium' => '✅ Unlimited'
                ],
                'CV Builder Access' => [
                    'free' => '✅ Yes',
                    'premium' => '✅ Yes'
                ],
                'CV Templates' => [
                    'free' => '✅ All 5 Templates',
                    'premium' => '✅ All 5 Templates'
                ],
                'CV Exports per Month' => [
                    'free' => '✅ Unlimited',
                    'premium' => '✅ Unlimited'
                ],
                'CV Customization' => [
                    'free' => '✅ Full Access',
                    'premium' => '✅ Full Access'
                ],
                'CV Watermark' => [
                    'free' => '✅ No Watermark',
                    'premium' => '✅ No Watermark'
                ],
                'Job Analytics' => [
                    'free' => '✅ Advanced Analytics',
                    'premium' => '✅ Advanced Analytics'
                ],
                'Interview Calendar' => [
                    'free' => '✅ Yes',
                    'premium' => '✅ Yes'
                ],
                'Interview Reminders' => [
                    'free' => '✅ Enabled',
                    'premium' => '✅ Enabled'
                ],
                'Goals Tracking' => [
                    'free' => '✅ Unlimited',
                    'premium' => '✅ Unlimited'
                ],
                'Export to CSV' => [
                    'free' => '✅ Yes',
                    'premium' => '✅ Yes'
                ]
            ];
        } else {
            // LIMITED features for FREE, FULL for PREMIUM when monetization is ON
            return [
                'Job Application Tracker' => [
                    'free' => '⚠️ Limited (Max 30 apps)',
                    'premium' => '✅ Unlimited'
                ],
                'CV Builder Access' => [
                    'free' => '✅ Yes',
                    'premium' => '✅ Yes'
                ],
                'CV Templates' => [
                    'free' => '⚠️ Only 1 Template',
                    'premium' => '✅ All 5 Templates'
                ],
                'CV Exports per Month' => [
                    'free' => '✅ Unlimited',
                    'premium' => '✅ Unlimited'
                ],
                'CV Customization' => [
                    'free' => '✅ Full Access',
                    'premium' => '✅ Full Access'
                ],
                'CV Watermark' => [
                    'free' => '✅ No Watermark',
                    'premium' => '✅ No Watermark'
                ],
                'Job Analytics' => [
                    'free' => '✅ Advanced Analytics',
                    'premium' => '✅ Advanced Analytics'
                ],
                'Interview Calendar' => [
                    'free' => '✅ Yes',
                    'premium' => '✅ Yes'
                ],
                'Interview Reminders' => [
                    'free' => '✅ Enabled',
                    'premium' => '✅ Enabled'
                ],
                'Goals Tracking' => [
                    'free' => '⚠️ Max 5 Goals',
                    'premium' => '✅ Unlimited'
                ],
                'Export to CSV' => [
                    'free' => '✅ Yes',
                    'premium' => '✅ Yes'
                ]
            ];
        }
    }
}
