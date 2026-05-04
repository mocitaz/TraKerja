<div x-data="{ show: @entangle('showModal') }">
    <template x-teleport="body">
        <div x-show="show"
             x-cloak
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
            
            {{-- Uniform Full-Screen Backdrop --}}
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="show = false"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xl transition-all"></div>

            {{-- Compact & Tidy Modal --}}
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative w-full max-w-lg bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden">
                
                <div class="p-8">
                    {{-- Header: Compact --}}
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 p-2">
                                <img src="{{ asset('images/icon.png') }}" class="w-full h-full object-contain" alt="TraKerja">
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 leading-none mb-1">TraKerja Sites</h3>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Publish Portfolio</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-slate-300 hover:text-slate-900 transition-colors">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <div class="space-y-6">
                        {{-- Input: Compact --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">URL Slug</label>
                            <div class="flex items-center px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl focus-within:bg-white focus-within:border-primary-500/20 focus-within:ring-4 focus-within:ring-primary-500/5 transition-all">
                                <span class="text-slate-400 font-bold text-xs select-none">trakerja.com/@</span>
                                <input type="text" 
                                       wire:model.live="slug"
                                       class="flex-1 bg-transparent border-none p-0 ml-1 focus:ring-0 font-bold text-slate-700 text-sm"
                                       placeholder="username">
                            </div>
                            @error('slug') <span class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Status Card: Tidy --}}
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $isPublished ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></span>
                                    <span class="text-[9px] font-black {{ $isPublished ? 'text-emerald-600' : 'text-slate-400' }} uppercase tracking-widest">
                                        {{ $isPublished ? 'Site is Live' : 'Not Published' }}
                                    </span>
                                </div>
                                @if($isPublished)
                                    <a href="{{ $portfolioUrl }}" target="_blank" class="text-[9px] font-black text-primary-600 uppercase tracking-widest hover:underline">Preview Site</a>
                                @endif
                            </div>
                            <p class="text-[11px] font-bold text-slate-500 truncate bg-white p-2.5 rounded-lg border border-slate-100">{{ $portfolioUrl }}</p>
                        </div>

                        {{-- Highlights: Extra Compact --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-2.5">
                                <i class="ph-bold ph-rocket-launch text-primary-500"></i>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-wide">New Opportunity</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <i class="ph-bold ph-chart-line-up text-primary-500"></i>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-wide">Growth Tracking</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions: Integrated --}}
                    <div class="mt-8 flex gap-3">
                        @if($isPublished)
                            <button wire:click="unpublish" class="px-5 py-3.5 bg-white text-rose-500 border border-rose-100 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-50 transition-all active:scale-95">
                                Unpublish
                            </button>
                            <button wire:click="publish" class="flex-1 px-5 py-3.5 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg active:scale-95">
                                Update URL
                            </button>
                        @else
                            <button wire:click="publish" class="w-full px-5 py-4 bg-primary-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 flex items-center justify-center gap-2 active:scale-95">
                                <i class="ph-bold ph-rocket-launch"></i>
                                Publish Portfolio
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
