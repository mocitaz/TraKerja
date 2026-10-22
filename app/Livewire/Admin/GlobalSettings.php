<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;

class GlobalSettings extends Component
{
    public $appName;
    public $contactEmail;
    
    public $aiLimitFree;
    public $aiLimitPremium;
    public $jobLimitFree;
    
    public function mount()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->appName = Setting::get('app_name', config('app.name', 'TraKerja'));
        $this->contactEmail = Setting::get('contact_email', 'support@trakerja.com');
        
        $this->aiLimitFree = Setting::get('limit_ai_analyzer_free', 1);
        $this->aiLimitPremium = Setting::get('limit_ai_analyzer_premium', 5);
        $this->jobLimitFree = Setting::get('limit_job_applications_free', 50);
    }
    
    public function updateIdentity()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->validate([
            'appName' => 'required|string|max:50',
            'contactEmail' => 'required|email|max:100',
        ]);
        
        Setting::set('app_name', $this->appName);
        Setting::set('contact_email', $this->contactEmail);
        
        session()->flash('success_identity', __('Platform identity saved.'));
    }
    
    public function updateLimits()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->validate([
            'aiLimitFree' => 'required|integer|min:0',
            'aiLimitPremium' => 'required|integer|min:0',
            'jobLimitFree' => 'required|integer|min:1',
        ]);
        
        Setting::set('limit_ai_analyzer_free', $this->aiLimitFree);
        Setting::set('limit_ai_analyzer_premium', $this->aiLimitPremium);
        Setting::set('limit_job_applications_free', $this->jobLimitFree);
        
        session()->flash('success_limits', __('Quota defaults saved.'));
    }

    public function render()
    {
        return view('livewire.admin.global-settings')
            ->layout('components.admin-layout');
    }
}
