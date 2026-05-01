<div class="pb-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pt-2">
        {{-- API Key Management --}}
        <div class="lg:col-span-7 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden group hover:border-primary-100 transition-all">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="ph-duotone ph-key text-lg text-primary-500"></i>
                    Main API Access
                </h4>
                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded uppercase">Secure</span>
            </div>
            
            <div class="p-8">
                <p class="text-xs text-slate-500 mb-8 leading-relaxed font-medium">
                    Gunakan API Key ini untuk mengintegrasikan data lamaran TraKerja ke portal loker atau sistem rekrutmen eksternal. <span class="text-red-500 font-bold">Jangan berikan key ini kepada siapapun!</span>
                </p>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Secret API Key</label>
                        <div class="relative group/key">
                            <input type="text" readonly value="{{ $apiKey ?: 'No API Key Generated' }}" 
                                class="w-full pl-5 pr-12 py-4 bg-slate-900 text-emerald-400 font-mono text-sm rounded-2xl border-none focus:ring-0">
                            <button wire:click="generateApiKey" class="absolute right-3 top-1/2 -translate-y-1/2 p-2 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all" title="Regenerate Key">
                                <i class="ph-bold ph-arrows-clockwise"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-primary-50 rounded-2xl border border-primary-100">
                        <i class="ph-duotone ph-info text-2xl text-primary-600"></i>
                        <p class="text-[11px] font-bold text-primary-700 leading-tight">
                            Setiap pendaftaran lamaran baru akan mengirimkan payload JSON lengkap ke sistem tujuan yang Anda atur.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Webhook Configuration --}}
        <div class="lg:col-span-5 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="ph-duotone ph-broadcast text-lg text-secondary-500"></i>
                    Webhook Outbound
                </h4>
            </div>

            <div class="p-8 flex-1 flex flex-col justify-between">
                <form wire:submit.prevent="saveWebhook" class="space-y-8">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Enable Webhook</p>
                                <p class="text-[10px] text-slate-500 font-medium">Aktifkan pengiriman data otomatis</p>
                            </div>
                            <button type="button" wire:click="$set('webhookEnabled', {{ !$webhookEnabled ? 'true' : 'false' }})" 
                                class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors focus:outline-none {{ $webhookEnabled ? 'bg-primary-600' : 'bg-slate-300' }}">
                                <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform duration-200 {{ $webhookEnabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Target Endpoint URL</label>
                            <input type="url" wire:model="webhookUrl" placeholder="https://api.partner.com/v1/webhook"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-3 active:scale-95">
                        <i class="ph-bold ph-check-circle text-lg"></i>
                        Save Webhook Config
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
