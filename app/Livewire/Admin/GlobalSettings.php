<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;

class GlobalSettings extends Component
{
    // Platform Identity
    public $appName;
    public $contactEmail;
    
    // Maintenance Mode
    public $maintenanceMode = false;
    
    // Limits
    public $aiLimitFree;
    public $aiLimitPremium;
    public $jobLimitFree;
    
    public function mount()
    {
        $this->appName = Setting::get('app_name', config('app.name', 'TraKerja'));
        $this->contactEmail = Setting::get('contact_email', 'support@trakerja.com');
        $this->maintenanceMode = (bool) Setting::get('maintenance_mode', false);
        
        $this->aiLimitFree = Setting::get('limit_ai_analyzer_free', 1);
        $this->aiLimitPremium = Setting::get('limit_ai_analyzer_premium', 5);
        $this->jobLimitFree = Setting::get('limit_job_applications_free', 50);
    }
    
    public function updateIdentity()
    {
        $this->validate([
            'appName' => 'required|string|max:50',
            'contactEmail' => 'required|email|max:100',
        ]);
        
        Setting::set('app_name', $this->appName);
        Setting::set('contact_email', $this->contactEmail);
        
        session()->flash('success_identity', 'Identitas platform berhasil diperbarui.');
    }
    
    public function toggleMaintenance()
    {
        $this->maintenanceMode = !$this->maintenanceMode;
        Setting::set('maintenance_mode', $this->maintenanceMode);
        
        if ($this->maintenanceMode) {
            // Kita bisa juga menggunakan native laravel, tapi karena kita mau soft maintenance, 
            // kita gunakan custom middleware nantinya yang membaca Setting ini.
            session()->flash('warning_maintenance', 'Maintenance Mode AKTIF. Pengguna biasa tidak dapat mengakses aplikasi.');
        } else {
            session()->flash('success_maintenance', 'Maintenance Mode DIMATIKAN. Aplikasi kembali normal.');
        }
    }
    
    public function updateLimits()
    {
        $this->validate([
            'aiLimitFree' => 'required|integer|min:0',
            'aiLimitPremium' => 'required|integer|min:0',
            'jobLimitFree' => 'required|integer|min:1',
        ]);
        
        Setting::set('limit_ai_analyzer_free', $this->aiLimitFree);
        Setting::set('limit_ai_analyzer_premium', $this->aiLimitPremium);
        Setting::set('limit_job_applications_free', $this->jobLimitFree);
        
        session()->flash('success_limits', 'Batas kuota API berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.admin.global-settings')
            ->layout('components.admin-layout');
    }
}
