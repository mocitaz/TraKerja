<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class IntegrationHub extends Component
{
    public $apiKey;
    public $webhookUrl;
    public $webhookEnabled = false;

    public function mount()
    {
        $this->apiKey = Setting::get('api_key', '');
        $this->webhookUrl = Setting::get('webhook_url', '');
        $this->webhookEnabled = (bool) Setting::get('webhook_enabled', false);
    }

    public function generateApiKey()
    {
        $this->apiKey = 'tk_' . Str::random(32);
        Setting::set('api_key', $this->apiKey);
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'API Key Generated!',
            'message' => 'API Key baru telah berhasil dibuat.',
        ]);
    }

    public function saveWebhook()
    {
        Setting::set('webhook_url', $this->webhookUrl);
        Setting::set('webhook_enabled', $this->webhookEnabled);

        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Webhook Saved!',
            'message' => 'Konfigurasi webhook telah diperbarui.',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.integration-hub')
            ->layout('components.admin-layout');
    }
}
