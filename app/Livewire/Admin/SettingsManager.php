<?php

namespace App\Livewire\Admin;
use App\Models\AppSetting;
use Livewire\Component;
class SettingsManager extends Component
{
    public $settings = [];
    public $activeTab = 'pricing';
    
    // Form fields
    public $selectedSetting;
    public $editValue;
    public $showEditModal = false;
    // Success/Error messages
    public $successMessage = '';
    public $errorMessage = '';
    public function mount()
    {
        $this->loadSettings();
    }
    public function loadSettings()
        $this->settings = AppSetting::orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy('group');
    public function setTab($tab)
        $this->activeTab = $tab;
    public function editSetting($id)
        $this->selectedSetting = AppSetting::find($id);
        $this->editValue = $this->selectedSetting->value;
        $this->showEditModal = true;
    public function saveSetting()
        $this->validate([
            'editValue' => 'required',
        ]);
        try {
            // Validate based on type
            if ($this->selectedSetting->type === 'number') {
                $metadata = $this->selectedSetting->metadata;
                $min = $metadata['min'] ?? 0;
                $max = $metadata['max'] ?? PHP_INT_MAX;
                
                if ($this->editValue < $min || $this->editValue > $max) {
                    throw new \Exception("Value must be between {$min} and {$max}");
                }
            }
            $this->selectedSetting->value = $this->editValue;
            $this->selectedSetting->save();
            $this->successMessage = "Setting '{$this->selectedSetting->key}' berhasil diupdate!";
            $this->showEditModal = false;
            $this->loadSettings();
            
            // Dispatch browser event for notification
            $this->dispatch('showNotification', [
                'type' => 'success',
                'title' => 'Settings Updated',
                'message' => $this->successMessage,
            ]);
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
                'type' => 'error',
                'title' => 'Update Failed',
                'message' => $this->errorMessage,
        }
    public function toggleActive($id)
        $setting = AppSetting::find($id);
        $setting->is_active = !$setting->is_active;
        $setting->save();
        $status = $setting->is_active ? 'activated' : 'deactivated';
        
        $this->dispatch('showNotification', [
            'type' => 'info',
            'title' => 'Status Changed',
            'message' => "Setting '{$setting->key}' has been {$status}",
    public function closeModal()
        $this->showEditModal = false;
        $this->selectedSetting = null;
        $this->editValue = '';
        $this->errorMessage = '';
    public function quickUpdatePrice($newPrice)
            AppSetting::set('premium_price', $newPrice);
                'title' => 'Price Updated',
                'message' => 'Premium price updated to Rp ' . number_format($newPrice),
                'message' => $e->getMessage(),
    public function testFeatureAccess()
        // For debugging/testing feature flags
        $testResults = [
            'CV Basic (Free User)' => AppSetting::isFeatureAvailable('feature_cv_basic', false),
            'CV Premium (Free User)' => AppSetting::isFeatureAvailable('feature_cv_premium_templates', false),
            'CV Premium (Premium User)' => AppSetting::isFeatureAvailable('feature_cv_premium_templates', true),
        ];
        session()->flash('test_results', $testResults);
    public function render()
        $premiumPrice = AppSetting::getPremiumPrice();
        $discountedPrice = AppSetting::getDiscountedPrice();
        $hasDiscount = $premiumPrice !== $discountedPrice;
        return view('livewire.admin.settings-manager', [
            'premiumPrice' => $premiumPrice,
            'discountedPrice' => $discountedPrice,
            'hasDiscount' => $hasDiscount,
}
