<div x-data="{ show: @entangle('showModal') }">
    <template x-teleport="body">
        <div x-show="show"
             x-cloak
             class="fixed inset-0 z-[9999] bg-zinc-950/40 backdrop-blur-xs overflow-y-auto hidden"
             :class="{ 'hidden': !show, 'flex': show }">
            <div class="flex min-h-full items-center justify-center p-4 w-full" @click.self="show = false">
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full border border-zinc-200 overflow-hidden text-left" @click.stop>
                    
                    <!-- Modal Header: Clean White -->
                    <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/60 flex items-center justify-center shadow-3xs">
                                <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-4 h-4 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <h3 class="text-xs font-bold text-zinc-800 tracking-tight">TraKerja Sites</h3>
                                    <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/60 leading-none">Portfolio</span>
                                </div>
                                <p class="text-zinc-400 text-[9px] font-medium mt-0.5">Career Growth Tracking</p>
                            </div>
                        </div>
                        <button type="button" @click="show = false" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 transition-all text-zinc-400 hover:text-zinc-800 focus:outline-none">
                            <i class="ph ph-x text-sm"></i>
                        </button>
                    </div>

                    <div class="p-4 sm:p-5">
                        <div class="space-y-4">
                            {{-- Input: URL Slug --}}
                            <div>
                                <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">URL Slug</label>
                                <div class="flex items-center bg-zinc-50/50 border border-zinc-200 rounded-md overflow-hidden transition-colors focus-within:bg-white focus-within:ring-1 focus-within:ring-primary-500/20 focus-within:border-primary-500">
                                    <span class="bg-zinc-100 border-r border-zinc-200 text-zinc-400 font-semibold text-xs px-2.5 py-1.5 select-none shrink-0">trakerja.com/@</span>
                                    <input type="text" 
                                           wire:model.live="slug"
                                           class="w-full px-3 py-1.5 bg-transparent border-none text-xs font-semibold text-zinc-700 focus:ring-0 focus:outline-none placeholder-zinc-300"
                                           placeholder="username">
                                </div>
                                @error('slug') <p class="text-rose-500 text-[9px] mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Status Card: Tidy --}}
                            <div class="p-4 bg-zinc-50/50 border border-zinc-200 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isPublished ? 'bg-emerald-500 animate-pulse' : 'bg-zinc-300' }}"></span>
                                        <span class="text-[9px] font-bold {{ $isPublished ? 'text-emerald-600' : 'text-zinc-400' }} uppercase tracking-wider leading-none">
                                            {{ $isPublished ? 'Site is Live' : 'Not Published' }}
                                        </span>
                                    </div>
                                    @if($isPublished)
                                        <a href="{{ $portfolioUrl }}" target="_blank" class="text-[9px] font-bold text-primary-650 uppercase tracking-wider hover:underline flex items-center gap-1 leading-none">
                                            <span>Preview Site</span>
                                            <i class="ph ph-arrow-square-out text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                                <p class="text-xs font-semibold text-zinc-550 bg-white px-3 py-1.5 rounded-md border border-zinc-200 truncate select-all cursor-pointer leading-none">{{ $portfolioUrl }}</p>
                            </div>

                            {{-- Theme Selector --}}
                            <div>
                                <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Portfolio Theme</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center gap-2 p-2 bg-zinc-50 border border-zinc-200 rounded-md cursor-pointer hover:bg-zinc-100/60 transition-colors">
                                        <input type="radio" name="portfolio_theme" value="slate" checked class="text-primary-600 focus:ring-0">
                                        <span class="text-[10px] font-bold text-zinc-700">Minimal Slate</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-zinc-50 border border-zinc-200 rounded-md cursor-pointer hover:bg-zinc-100/60 transition-colors">
                                        <input type="radio" name="portfolio_theme" value="dark" class="text-primary-600 focus:ring-0">
                                        <span class="text-[10px] font-bold text-zinc-700">Obsidian Dark</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-zinc-50 border border-zinc-200 rounded-md cursor-pointer hover:bg-zinc-100/60 transition-colors">
                                        <input type="radio" name="portfolio_theme" value="emerald" class="text-primary-600 focus:ring-0">
                                        <span class="text-[10px] font-bold text-zinc-700">Emerald Luxe</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-zinc-50 border border-zinc-200 rounded-md cursor-pointer hover:bg-zinc-100/60 transition-colors">
                                        <input type="radio" name="portfolio_theme" value="violet" class="text-primary-600 focus:ring-0">
                                        <span class="text-[10px] font-bold text-zinc-700">Royal Violet</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Custom Domain --}}
                            <div>
                                <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1.5">Custom Domain (CNAME)</label>
                                <input type="text" placeholder="e.g. karir.domainanda.com" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 focus:bg-white focus:border-primary-500 outline-none">
                                <p class="text-[8.5px] text-zinc-400 mt-1">Point A/CNAME record to <span class="font-bold text-zinc-600">cname.trakerja.com</span></p>
                            </div>
                        </div>

                        {{-- Actions: Integrated --}}
                        <div class="mt-5 flex gap-2.5 pt-4 border-t border-zinc-150/60">
                            @if($isPublished)
                                <button type="button" wire:click="unpublish" class="px-3.5 h-8 bg-white text-rose-600 border border-rose-250 rounded-md text-xs font-bold hover:bg-rose-50 transition-all active:scale-97 focus:outline-none">
                                    Unpublish
                                </button>
                                <button type="button" wire:click="publish" class="flex-1 h-8 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded-md text-xs font-bold hover:bg-primary-100 transition-all shadow-3xs flex items-center justify-center gap-1.5 active:scale-97 focus:outline-none">
                                    Update URL
                                </button>
                            @else
                                <button type="button" wire:click="publish" class="w-full h-8 bg-primary-50 text-zinc-800 border border-primary-200/60 rounded-md text-xs font-bold hover:bg-primary-100 transition-all shadow-3xs flex items-center justify-center gap-2 active:scale-97 focus:outline-none">
                                    <i class="ph-bold ph-rocket-launch text-sm"></i>
                                    <span>Publish Portfolio</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
