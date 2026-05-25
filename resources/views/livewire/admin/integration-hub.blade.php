<div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200/60 rounded-[1.25rem] sm:rounded-[1.5rem] flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                <i class="ph-duotone ph-plugs-connected text-xl sm:text-2xl"></i>
            </div>
            <div class="flex flex-col min-w-0">
                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate">Integration Hub</h3>
                <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1 sm:mt-1.5 truncate">API & Webhook Settings</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 pt-2">
        {{-- API Key Management --}}
        <div class="lg:col-span-7 bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-50 rounded-xl border border-primary-100 flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-key text-xl"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 tracking-tight truncate">Main API Access</h4>
                </div>
                <span class="px-3 py-1.5 bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-black tracking-widest rounded-lg uppercase shadow-sm">Secure</span>
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
        <div class="lg:col-span-5 bg-white rounded-[2rem] border border-slate-200/60 bento-card overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-secondary-50 rounded-xl border border-secondary-100 flex items-center justify-center text-secondary-600 shadow-sm shrink-0">
                        <i class="ph-duotone ph-broadcast text-xl"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 tracking-tight truncate">Webhook Outbound</h4>
                </div>
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
