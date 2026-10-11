<div class="space-y-5">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 text-left">

        {{-- API Key Management --}}
        <div class="lg:col-span-7 ast-panel">
            <div class="ast-panel-heading">
                <h3>
                    <i class="ph ph-key" aria-hidden="true"></i>
                    Main API Access Key
                </h3>
                <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-lg">
                    Production Secure
                </span>
            </div>

            <div class="ast-panel-body justify-between">
                <p class="text-xs text-gray-500 leading-relaxed">
                    Use this secret API key to integrate TraKerja application data with external job boards or corporate HRIS systems. Keep this secret safe and never share it publicly.
                </p>

                <div class="space-y-3">
                    <div class="ast-form-group">
                        <label>Secret API Key</label>
                        <div class="ast-api-key-box">
                            <input type="text" readonly value="{{ $apiKey ?: 'No API Key Generated' }}" class="ast-api-key-input">
                            <button type="button" 
                                    wire:click="generateApiKey" 
                                    class="p-2 text-gray-400 hover:text-white rounded-lg transition-colors"
                                    title="Regenerate Key">
                                <i class="ph ph-arrows-clockwise text-base" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div class="ast-info-note">
                        <i class="ph ph-info" aria-hidden="true"></i>
                        <span>New job application dispatches will stream complete JSON payloads to your configured endpoint securely.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Webhook Configuration --}}
        <div class="lg:col-span-5 ast-panel">
            <div class="ast-panel-heading">
                <h3>
                    <i class="ph ph-broadcast" aria-hidden="true"></i>
                    Outbound Webhooks
                </h3>
            </div>

            <div class="ast-panel-body">
                <form wire:submit.prevent="saveWebhook" class="space-y-4">
                    <div class="ast-toggle-item">
                        <div>
                            <strong>Enable Webhook</strong>
                            <p>Automatically push candidate events</p>
                        </div>
                        <button type="button" 
                                wire:click="$set('webhookEnabled', {{ !$webhookEnabled ? 'true' : 'false' }})"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $webhookEnabled ? 'bg-blue-600' : 'bg-gray-300' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 {{ $webhookEnabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>

                    <div class="ast-form-group">
                        <label>Target Endpoint URL</label>
                        <input type="url" wire:model="webhookUrl" placeholder="https://api.partner.com/v1/webhook">
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="ast-btn ast-btn--primary">
                            <i class="ph ph-check-circle" aria-hidden="true"></i>
                            Save Webhook Config
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
