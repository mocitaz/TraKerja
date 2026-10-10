<div class="space-y-4">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 text-left">
        {{-- API Key Management --}}
        <div class="lg:col-span-7 ops-legacy-panel bg-white rounded-2xl border border-[#e7e3e9] overflow-hidden flex flex-col ">
            <div class="px-5 py-4 border-b border-zinc-100 bg-zinc-50/20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded bg-zinc-50 border border-[#e7e3e9] flex items-center justify-center shrink-0 ">
                        <i class="ph-bold ph-key text-zinc-500 text-xs"></i>
                    </div>
                    <h4 class="text-xs font-medium text-zinc-900 tracking-tight">Main API Access</h4>
                </div>
                <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-250 text-emerald-700 text-[11px] font-sans font-medium  rounded-lg ">Secure</span>
            </div>

            <div class="p-5 space-y-4 flex-1 flex flex-col justify-between">
                <p class="text-[11px] text-zinc-500 leading-relaxed font-sans font-medium">
                    Gunakan API Key ini untuk mengintegrasikan data lamaran TraKerja ke portal loker atau sistem rekrutmen eksternal. <span class="text-red-650 font-semibold font-sans">Jangan berikan key ini kepada siapapun!</span>
                </p>

                <div class="space-y-3">
                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-sans font-medium text-zinc-500  ">Secret API Key</label>
                        <div class="relative flex items-center  rounded-lg overflow-hidden">
                            <input type="text" readonly value="{{ $apiKey ?: 'No API Key Generated' }}"
                                   class="w-full pl-3 pr-10 py-3 bg-zinc-950 text-emerald-400 font-sans text-xs border-none focus:ring-0">
                            <button type="button" wire:click="generateApiKey"
                                    class="absolute right-2 p-1.5 hover:bg-white/10 text-white rounded-lg transition-colors focus:outline-none"
                                    title="Regenerate Key">
                                <i class="ph-bold ph-arrows-clockwise"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5 p-3.5 bg-zinc-50 rounded-2xl border border-[#e7e3e9]">
                        <i class="ph-bold ph-info text-sm text-zinc-500 mt-0.5"></i>
                        <p class="text-[11px] text-zinc-500 leading-tight font-medium">
                            Setiap pendaftaran lamaran baru akan mengirimkan payload JSON lengkap ke sistem tujuan yang Anda atur.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Webhook Configuration --}}
        <div class="lg:col-span-5 ops-legacy-panel bg-white rounded-2xl border border-[#e7e3e9] overflow-hidden flex flex-col ">
            <div class="px-5 py-4 border-b border-zinc-100 bg-zinc-50/20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded bg-zinc-50 border border-[#e7e3e9]/40 flex items-center justify-center shrink-0 ">
                        <i class="ph-bold ph-broadcast text-zinc-550 text-xs"></i>
                    </div>
                    <h4 class="text-xs font-medium text-zinc-900 tracking-tight">Webhook Outbound</h4>
                </div>
            </div>

            <div class="p-5 flex-1 flex flex-col justify-between">
                <form wire:submit.prevent="saveWebhook" class="space-y-4 flex flex-col justify-between h-full">
                    <div class="space-y-4">
                        <div class="p-3.5 bg-zinc-50 rounded-2xl border border-[#e7e3e9] flex items-center justify-between ">
                            <div>
                                <p class="text-xs font-medium text-zinc-800 leading-none">Enable Webhook</p>
                                <p class="text-[11px] text-zinc-500 mt-1 font-semibold">Aktifkan pengiriman data otomatis</p>
                            </div>
                            <button type="button" wire:click="$set('webhookEnabled', {{ !$webhookEnabled ? 'true' : 'false' }})"
                                    class="relative inline-flex h-5.5 w-9.5 items-center rounded-full transition-colors focus:outline-none  {{ $webhookEnabled ? 'bg-zinc-900' : 'bg-zinc-350' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 {{ $webhookEnabled ? 'translate-x-4.5' : 'translate-x-1' }}"></span>
                            </button>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-sans font-medium text-zinc-500   font-medium">Target Endpoint URL</label>
                            <input type="url" wire:model="webhookUrl" placeholder="https://api.partner.com/v1/webhook"
                                   class="w-full px-3 h-9 bg-white border border-[#e7e3e9] rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none focus:border-zinc-450 transition-colors ">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-100 flex justify-end mt-4">
                        <button type="submit"
                                class="w-full h-9 px-4 bg-zinc-900 text-white rounded-lg text-xs font-medium hover:bg-zinc-800 transition-colors flex items-center justify-center gap-1.5 focus:outline-none ">
                            <i class="ph-bold ph-check-circle text-xs"></i>
                            Save Webhook Config
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
