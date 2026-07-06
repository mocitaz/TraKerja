<div class="space-y-4">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 text-left">
        {{-- API Key Management --}}
        <div class="lg:col-span-7 bg-white rounded border border-zinc-200/60 overflow-hidden flex flex-col shadow-none">
            <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                        <i class="ph ph-key text-xs"></i>
                    </div>
                    <h4 class="text-xs font-bold text-zinc-900 tracking-tight">Main API Access</h4>
                </div>
                <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-150 text-emerald-700 text-[8px] font-mono font-bold tracking-wider rounded uppercase">Secure</span>
            </div>
            
            <div class="p-4 space-y-4 flex-1 flex flex-col justify-between">
                <p class="text-[11px] text-zinc-500 leading-relaxed font-sans">
                    Gunakan API Key ini untuk mengintegrasikan data lamaran TraKerja ke portal loker atau sistem rekrutmen eksternal. <span class="text-red-600 font-semibold font-mono">Jangan berikan key ini kepada siapapun!</span>
                </p>

                <div class="space-y-3">
                    <div class="space-y-1.5">
                        <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Secret API Key</label>
                        <div class="relative flex items-center">
                            <input type="text" readonly value="{{ $apiKey ?: 'No API Key Generated' }}" 
                                   class="w-full pl-3 pr-10 py-2.5 bg-zinc-950 text-emerald-400 font-mono text-xs rounded border-none focus:ring-0">
                            <button type="button" wire:click="generateApiKey" 
                                    class="absolute right-2 p-1.5 hover:bg-white/10 text-white rounded transition-colors focus:outline-none" 
                                    title="Regenerate Key">
                                <i class="ph ph-arrows-clockwise"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5 p-3 bg-zinc-50 rounded border border-zinc-200/80">
                        <i class="ph ph-info text-sm text-zinc-500 mt-0.5"></i>
                        <p class="text-[10px] text-zinc-500 leading-tight">
                            Setiap pendaftaran lamaran baru akan mengirimkan payload JSON lengkap ke sistem tujuan yang Anda atur.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Webhook Configuration --}}
        <div class="lg:col-span-5 bg-white rounded border border-zinc-200/60 overflow-hidden flex flex-col shadow-none">
            <div class="px-4 py-3 border-b border-zinc-150/60 bg-zinc-50/20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded bg-zinc-50 border border-zinc-200/40 text-zinc-500 flex items-center justify-center shrink-0">
                        <i class="ph ph-broadcast text-xs"></i>
                    </div>
                    <h4 class="text-xs font-bold text-zinc-900 tracking-tight">Webhook Outbound</h4>
                </div>
            </div>

            <div class="p-4 flex-1 flex flex-col justify-between">
                <form wire:submit.prevent="saveWebhook" class="space-y-4 flex flex-col justify-between h-full">
                    <div class="space-y-4">
                        <div class="p-3 bg-zinc-50 rounded border border-zinc-200/80 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-zinc-800 leading-none">Enable Webhook</p>
                                <p class="text-[10px] text-zinc-400 mt-1">Aktifkan pengiriman data otomatis</p>
                            </div>
                            <button type="button" wire:click="$set('webhookEnabled', {{ !$webhookEnabled ? 'true' : 'false' }})" 
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none shadow-none {{ $webhookEnabled ? 'bg-zinc-900' : 'bg-zinc-300' }}">
                                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform duration-200 {{ $webhookEnabled ? 'translate-x-4' : 'translate-x-1' }}"></span>
                            </button>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[8px] font-mono font-bold text-zinc-400 uppercase tracking-wide">Target Endpoint URL</label>
                            <input type="url" wire:model="webhookUrl" placeholder="https://api.partner.com/v1/webhook"
                                   class="w-full px-3 h-8 bg-white border border-zinc-250 rounded text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 transition-colors">
                        </div>
                    </div>

                    <div class="pt-3 border-t border-zinc-150/60 flex justify-end mt-4">
                        <button type="submit" 
                                class="w-full h-8 px-4 bg-zinc-900 text-white rounded text-xs font-semibold hover:bg-zinc-800 transition-colors flex items-center justify-center gap-1.5 focus:outline-none shadow-none">
                            <i class="ph ph-check-circle text-xs"></i>
                            Save Webhook Config
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
