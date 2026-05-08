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
        $this->premiumPrice = Setting::get('premium_price', 19999);
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
        // Feature matrix shows comparison between FREE USERS vs PREMIUM USERS
        return [
            'Job Application Tracker' => [
                'free' => $this->monetizationEnabled ? 'Max 50 Applications' : 'Unlimited (Now)',
                'premium' => 'Unlimited',
                'free_status' => $this->monetizationEnabled ? 'limited' : 'full',
            ],
            'AI Resume Analyzer' => [
                'free' => $this->monetizationEnabled ? '1x Trial Use' : 'Unlimited (Now)',
                'premium' => '5 Uses/Month + Top-Up',
                'free_status' => $this->monetizationEnabled ? 'limited' : 'full',
            ],
            'AI Cover Letter Generator' => [
                'free' => $this->monetizationEnabled ? '3x Trial Uses' : 'Unlimited (Now)',
                'premium' => '5 Uses/Month + Top-Up',
                'free_status' => $this->monetizationEnabled ? 'limited' : 'full',
            ],
            'CV Builder Access' => [
                'free' => 'Full Access',
                'premium' => 'Yes',
                'free_status' => 'full',
            ],
            'CV Templates' => [
                'free' => $this->monetizationEnabled ? '2 Templates Only' : 'All 4 Templates (Now)',
                'premium' => 'All 4 Templates',
                'free_status' => $this->monetizationEnabled ? 'limited' : 'full',
            ],
            'CV Exports per Month' => [
                'free' => $this->monetizationEnabled ? '5 Exports/Month' : 'Unlimited (Now)',
                'premium' => 'Unlimited',
                'free_status' => $this->monetizationEnabled ? 'limited' : 'full',
            ],
            'CV Customization' => [
                'free' => 'Full Access',
                'premium' => 'Full Access',
                'free_status' => 'full',
            ],
            'CV Watermark' => [
                'free' => 'Full Access',
                'premium' => 'No Watermark',
                'free_status' => 'full',
            ],
            'Job Analytics' => [
                'free' => $this->monetizationEnabled ? 'Basic Analytics' : 'Advanced (Now)',
                'premium' => 'Advanced Analytics',
                'free_status' => $this->monetizationEnabled ? 'limited' : 'full',
            ],
            'Interview Calendar' => [
                'free' => 'Full Access',
                'premium' => 'Yes',
                'free_status' => 'full',
            ]
        ];
    }
}
