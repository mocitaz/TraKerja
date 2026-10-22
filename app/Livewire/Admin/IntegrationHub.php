<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Str;

class IntegrationHub extends Component
{
    public $apiKey;
    public $webhookUrl;
    public $webhookEnabled = false;

    public function mount()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->apiKey = Setting::get('api_key', '');
        $this->webhookUrl = Setting::get('webhook_url', '');
        $this->webhookEnabled = (bool) Setting::get('webhook_enabled', false);
    }

    public function generateApiKey()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->apiKey = 'tk_' . Str::random(32);
        Setting::set('api_key', $this->apiKey);

        session()->flash('integration_result', __('API key generated and saved.'));
        $this->dispatch('api-key-generated');
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => __('API Key Generated!'),
            'message' => __('API Key baru telah berhasil dibuat.'),
        ]);
    }

    public function saveWebhook()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->validate([
            'webhookEnabled' => 'required|boolean',
            'webhookUrl' => 'nullable|required_if:webhookEnabled,true|url:http,https|max:500',
        ]);
        Setting::set('webhook_url', $this->webhookUrl);
        Setting::set('webhook_enabled', $this->webhookEnabled);

        session()->flash('webhook_result', __('Webhook configuration saved.'));
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => __('Webhook Saved!'),
            'message' => __('Konfigurasi webhook telah diperbarui.'),
        ]);
    }

    public function render()
    {
        $mailer = config('mail.default');
        return view('livewire.admin.integration-hub', ['providers' => [
            ['name' => 'Gemini', 'purpose' => __('AI writing and resume analysis'), 'detail' => config('services.gemini.model'), 'configured' => (bool) config('services.gemini.key'), 'icon' => 'cpu'],
            ['name' => __('Email'), 'purpose' => __('Campaigns and account messages'), 'detail' => strtoupper((string) $mailer), 'configured' => $mailer === 'smtp' && (bool) config('mail.mailers.smtp.host'), 'icon' => 'envelope-simple'],
            ['name' => 'Pakasir', 'purpose' => __('Payment processing'), 'detail' => config('pakasir.sandbox') ? __('Sandbox mode') : __('Live mode'), 'configured' => (bool) config('pakasir.api_key') && (bool) config('pakasir.slug'), 'icon' => 'credit-card'],
            ['name' => 'Telegram', 'purpose' => __('Administrative notifications'), 'detail' => config('services.telegram.enabled') ? __('Enabled in configuration') : __('Disabled in configuration'), 'configured' => (bool) config('services.telegram.enabled') && (bool) config('services.telegram.bot_token'), 'icon' => 'chat-circle-text'],
        ]]);
    }
}
