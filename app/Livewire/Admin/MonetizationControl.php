<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\User;

class MonetizationControl extends Component
{
    public $currentPhase;
    public $newPhase;
    public $showConfirmation = false;
    
    // Stats
    public $totalUsers = 0;
    public $freeUsers = 0;
    public $premiumUsers = 0;
    
    public function mount()
    {
        $this->currentPhase = Setting::getMonetizationPhase();
        $this->loadStats();
    }
    
    public function loadStats()
    {
        $this->totalUsers = User::count();
        $this->freeUsers = User::where('is_premium', false)->count();
        $this->premiumUsers = User::where('is_premium', true)->count();
    }
    
    public function setPhase($phase)
    {
        // Don't confirm if same phase
        if ($phase == $this->currentPhase) {
            $this->dispatch('showNotification', [
                'type' => 'info',
                'title' => 'Same Phase',
                'message' => "Already in Phase {$phase}"
            ]);
            return;
        }
        
        $this->newPhase = $phase;
        $this->showConfirmation = true;
    }
    
    public function confirmSetPhase()
    {
        // Update phase
        Setting::set('monetization_phase', $this->newPhase);
        
        $oldPhase = $this->currentPhase;
        $this->currentPhase = $this->newPhase;
        $this->showConfirmation = false;
        
        // Clear settings cache
        Setting::clearCache();
        
        // Log change
        Log::info("Monetization phase changed from {$oldPhase} to {$this->newPhase}", [
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name,
            'timestamp' => now(),
            'total_users_affected' => $this->totalUsers
        ]);
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Phase Updated!',
            'message' => "Monetization phase successfully changed to Phase {$this->newPhase}: " . phase_name($this->newPhase)
        ]);
        
        // Reload stats
        $this->loadStats();
    }
    
    public function cancelSetPhase()
    {
        $this->newPhase = null;
        $this->showConfirmation = false;
    }
    
    public function render()
    {
        // Get feature matrix for current phase
        $featureMatrix = $this->buildFeatureMatrix();
        
        return view('livewire.admin.monetization-control', [
            'featureMatrix' => $featureMatrix,
            'phaseName' => phase_name($this->currentPhase),
            'phaseEmoji' => phase_emoji($this->currentPhase)
        ]);
    }
    
    private function buildFeatureMatrix()
    {
        $featureAccess = Setting::get('feature_access', []);
        $phaseKey = "phase_{$this->currentPhase}";
        $phaseConfig = $featureAccess[$phaseKey] ?? [];
        
        // Transform phase config into readable matrix
        $matrix = [
            'CV Templates' => [
                'free' => $phaseConfig['cv_templates_free'] ?? $phaseConfig['cv_templates'] ?? 5,
                'premium' => $phaseConfig['cv_templates_premium'] ?? $phaseConfig['cv_templates'] ?? 5
            ],
            'CV Customization' => [
                'free' => ($phaseConfig['cv_customization'] ?? 'free') === 'free',
                'premium' => true
            ],
            'CV Watermark' => [
                'free' => ($phaseConfig['cv_watermark_free'] ?? $phaseConfig['cv_watermark'] ?? false) ? 'Yes' : 'No',
                'premium' => ($phaseConfig['cv_watermark_premium'] ?? false) ? 'Yes' : 'No'
            ],
            'CV Exports per Month' => [
                'free' => $phaseConfig['cv_exports_free'] ?? $phaseConfig['cv_exports'] ?? 'unlimited',
                'premium' => $phaseConfig['cv_exports_premium'] ?? 'unlimited'
            ],
            'Saved CV Configs' => [
                'free' => $phaseConfig['saved_configs_free'] ?? $phaseConfig['saved_configs'] ?? 'unlimited',
                'premium' => $phaseConfig['saved_configs_premium'] ?? 'unlimited'
            ],
            'Advanced Analytics' => [
                'free' => ($phaseConfig['advanced_analytics'] ?? 'free') === 'free',
                'premium' => true
            ],
            'Interview Reminders' => [
                'free' => ($phaseConfig['interview_reminders'] ?? 'free') === 'free',
                'premium' => true
            ],
            'Calendar Export' => [
                'free' => ($phaseConfig['calendar_export'] ?? 'free') === 'free',
                'premium' => true
            ]
        ];
        
        return $matrix;
    }
}
