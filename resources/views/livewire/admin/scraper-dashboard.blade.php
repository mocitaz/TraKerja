<div wire:poll.5s class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-5 pb-10">
    
    {{-- Alert Messages --}}
    @if (session()->has('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-center gap-2.5 shadow-3xs animate-fade-in">
            <i class="ph-bold ph-check-circle text-base text-emerald-650 shrink-0"></i>
            <p class="text-xs font-semibold">{{ session('success') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-3.5 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center gap-2.5 shadow-3xs animate-fade-in">
            <i class="ph-bold ph-x-circle text-base text-red-600 shrink-0"></i>
            <p class="text-xs font-semibold">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Sticky Global Sub-Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-200/80">
        <div class="flex items-center gap-2.5 min-w-0">
            <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
            <span class="text-zinc-300">/</span>
            <span class="text-xs font-mono font-medium text-zinc-400">System</span>
            <span class="text-zinc-300">/</span>
            <h1 class="text-sm font-semibold tracking-tight text-zinc-900">Scraper Engine</h1>
        </div>
        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center px-2 py-1.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-1"></span>
                Engine Ready
            </span>
        </div>
    </div>

    {{-- Executive Summary Stats (Bento Grid) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Ingested --}}
        <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden shadow-3xs">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Total Ingested Jobs</p>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['total_ingested']) }}</h3>
                    <p class="text-[10px] text-zinc-400 mt-1">Aggregated job listings</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650 shrink-0">
                    <i class="ph-bold ph-database text-base"></i>
                </div>
            </div>
        </div>

        {{-- Jobs Scraped Today --}}
        <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden shadow-3xs">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Scraped Today</p>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ number_format($stats['jobs_scraped_today']) }}</h3>
                    <p class="text-[10px] text-zinc-400 mt-1">Ingested in last 24 hours</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650 shrink-0">
                    <i class="ph-bold ph-calendar-check text-base"></i>
                </div>
            </div>
        </div>

        {{-- Active Platforms --}}
        <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden shadow-3xs">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Active Platforms</p>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900">{{ $stats['active_sources'] }} / 3</h3>
                    <p class="text-[10px] text-zinc-400 mt-1">LinkedIn, JobStreet, Kalibrr</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650 shrink-0">
                    <i class="ph-bold ph-globe text-base"></i>
                </div>
            </div>
        </div>

        {{-- Queue & Worker Monitor --}}
        <div class="bg-white rounded-lg p-4 border border-zinc-200/80 relative overflow-hidden shadow-3xs">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[9px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-1">Worker & Queue Monitor</p>
                    <h3 class="text-sm font-bold tracking-tight text-zinc-900 mt-0.5 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $stats['jobs_pending_in_queue'] > 0 ? 'bg-amber-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                        {{ $stats['worker_status'] }}
                    </h3>
                    <p class="text-[10px] text-zinc-400 mt-1.5 font-semibold">{{ $stats['jobs_pending_in_queue'] }} jobs in queue</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650 shrink-0">
                    <i class="ph-bold ph-cpu text-base"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Interactive Tabs Navigation Row (4 Tabs) --}}
    <div class="flex border-b border-zinc-200 bg-white rounded-t-lg shadow-3xs">
        <button type="button" 
                wire:click="$set('activeTab', 'dashboard')"
                class="px-5 py-3.5 border-b-2 text-xs font-semibold tracking-tight transition-all duration-150 focus:outline-hidden flex items-center gap-2 {{ $activeTab === 'dashboard' ? 'border-zinc-950 text-zinc-950 bg-zinc-50/50' : 'border-transparent text-zinc-400 hover:text-zinc-700' }}">
            <i class="ph-bold ph-chart-line text-sm"></i> Overview & Grafik
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'control')"
                class="px-5 py-3.5 border-b-2 text-xs font-semibold tracking-tight transition-all duration-150 focus:outline-hidden flex items-center gap-2 {{ $activeTab === 'control' ? 'border-zinc-950 text-zinc-950 bg-zinc-50/50' : 'border-transparent text-zinc-400 hover:text-zinc-700' }}">
            <i class="ph-bold ph-sliders text-sm"></i> Kontrol Engine & Worker
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'target')"
                class="px-5 py-3.5 border-b-2 text-xs font-semibold tracking-tight transition-all duration-150 focus:outline-hidden flex items-center gap-2 {{ $activeTab === 'target' ? 'border-zinc-950 text-zinc-950 bg-zinc-50/50' : 'border-transparent text-zinc-400 hover:text-zinc-700' }}">
            <i class="ph-bold ph-crosshair text-sm"></i> Target-Driven Ingestion
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'sandbox')"
                class="px-5 py-3.5 border-b-2 text-xs font-semibold tracking-tight transition-all duration-150 focus:outline-hidden flex items-center gap-2 {{ $activeTab === 'sandbox' ? 'border-zinc-950 text-zinc-950 bg-zinc-50/50' : 'border-transparent text-zinc-400 hover:text-zinc-700' }}">
            <i class="ph-bold ph-terminal-window text-sm"></i> Sandbox & CSS Selector Tester
        </button>
    </div>

    <!-- Active Tab Workspace Container -->
    <div>
        @if ($activeTab === 'dashboard')
            <!-- TAB 1: OVERVIEW DASHBOARD & METRICS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch animate-fade-in text-zinc-900">
                <!-- Stacked Bar Performance Chart (2/3 width) -->
                <div class="lg:col-span-2 bg-white border border-zinc-200/80 rounded-lg p-4 flex flex-col gap-4 shadow-3xs">
                    <div class="flex-1 flex flex-col">
                        <div class="flex items-center gap-2 pb-3 mb-4 border-b border-zinc-100 shrink-0">
                            <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                                <i class="ph-bold ph-chart-bar text-xs"></i>
                            </div>
                            <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Metrik Grafik Performa Platform</h2>
                        </div>
                        
                        <div class="flex-1 min-h-[260px] relative w-full" wire:ignore>
                            <canvas id="scraperPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Live Log Streamer Console (1/3 width) -->
                <div class="lg:col-span-1 bg-white border border-zinc-200/80 rounded-lg p-4 flex flex-col justify-between shadow-3xs">
                    <div class="w-full">
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                                    <i class="ph-bold ph-terminal text-xs"></i>
                                </div>
                                <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider font-semibold">Live Logs Streamer</h2>
                            </div>
                            <button type="button" 
                                    wire:click="clearLiveLogs"
                                    class="text-[9px] font-mono font-bold text-rose-600 hover:text-rose-800 transition-colors uppercase tracking-wider flex items-center gap-1 focus:outline-hidden">
                                <i class="ph ph-trash text-xs"></i> Hapus Log
                            </button>
                        </div>

                        @if (count($liveLogs) > 0)
                            <div class="bg-[#121214] text-[#e4e4e7] border border-zinc-800 p-3 rounded-lg font-mono text-[9px] leading-relaxed shadow-inner max-h-[300px] overflow-y-auto space-y-1.5 custom-scrollbar">
                                @foreach ($liveLogs as $log)
                                    <div class="whitespace-pre-wrap tracking-tight flex items-start gap-1">
                                        <span class="text-zinc-500 shrink-0">[{{ $log['timestamp'] }}]</span>
                                        <span class="shrink-0 {{ $log['level'] === 'SUCCESS' ? 'text-emerald-500' : ($log['level'] === 'ERROR' ? 'text-rose-500' : ($log['level'] === 'WARNING' ? 'text-amber-500' : 'text-zinc-400')) }}">
                                            [{{ $log['level'] }}]
                                        </span>
                                        <span class="text-zinc-200 flex-1">{{ $log['message'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-zinc-50 border border-zinc-150 rounded-lg p-10 text-center flex flex-col items-center justify-center border-dashed">
                                <i class="ph ph-activity text-zinc-400 text-3xl mb-2"></i>
                                <p class="text-xs font-semibold text-zinc-600">Console logs empty</p>
                                <p class="text-[10px] text-zinc-400 max-w-[200px] mt-0.5">Logs akan tampil secara live ketika aktivitas scraping berjalan.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="text-[9px] font-mono text-zinc-400 pt-3 border-t border-zinc-150 leading-tight mt-4">
                        Polling visual terupdate secara berkala setiap 5 detik.
                    </div>
                </div>
            </div>
        @endif

        @if ($activeTab === 'control')
            <!-- TAB 2: ENGINE CONTROLLER & DIAGNOSTICS -->
            <div class="space-y-5 animate-fade-in text-zinc-900">
                <!-- Grid of 3 Action Controllers -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <!-- 1. Background Queue Worker Card -->
                    <div class="bg-white border border-zinc-200 rounded-lg p-5 flex flex-col justify-between shadow-3xs space-y-4">
                        <div>
                            <div class="flex items-center gap-2 pb-2.5 border-b border-zinc-100">
                                <div class="w-6 h-6 rounded bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700">
                                    <i class="ph-bold ph-cpu text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight">Queue Worker Trigger</h3>
                                    <p class="text-[9px] text-zinc-400">Jalankan worker di latar belakang VM</p>
                                </div>
                            </div>
                            
                            <div class="mt-3.5 space-y-2 text-[10px] font-mono">
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">Status Worker:</span>
                                    <span class="font-bold text-emerald-600 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        {{ $stats['worker_status'] }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">Jumlah Antrean:</span>
                                    <span class="font-bold text-zinc-800">{{ $stats['jobs_pending_in_queue'] }} jobs</span>
                                </div>
                            </div>
                        </div>

                        <button type="button" 
                                wire:click="startQueueWorker" 
                                wire:loading.attr="disabled"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-md py-2 transition-all duration-150 shadow-3xs flex items-center justify-center gap-1.5">
                            <span wire:loading.remove wire:target="startQueueWorker" class="flex items-center gap-1.5">
                                <i class="ph ph-play-circle text-base"></i>
                                Jalankan Queue Worker
                            </span>
                            <span wire:loading wire:target="startQueueWorker" class="flex items-center gap-1.5">
                                <span class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                Memicu Worker...
                            </span>
                        </button>
                    </div>

                    <!-- 2. Dead-Link Validator Card -->
                    <div class="bg-white border border-zinc-200 rounded-lg p-5 flex flex-col justify-between shadow-3xs space-y-4">
                        <div>
                            <div class="flex items-center gap-2 pb-2.5 border-b border-zinc-100">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                                    <i class="ph-bold ph-shield-warning text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight">Dead-Link Synchronizer</h3>
                                    <p class="text-[9px] text-zinc-400">Verifikasi status keaktifan URL lowongan</p>
                                </div>
                            </div>
                            
                            <div class="mt-3.5 space-y-2 text-[10px] font-mono">
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">Metode Validasi:</span>
                                    <span class="font-bold text-zinc-800">404 / Footprint Check</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">Target Validasi:</span>
                                    <span class="font-bold text-zinc-800">Loker Status 'active'</span>
                                </div>
                            </div>
                        </div>

                        <button type="button" 
                                wire:click="verifyActiveListings" 
                                wire:loading.attr="disabled"
                                class="w-full bg-white hover:bg-zinc-50 border border-zinc-250 text-zinc-700 font-semibold text-xs rounded-md py-2 transition-all duration-150 flex items-center justify-center gap-1.5 shadow-3xs">
                            <span wire:loading.remove wire:target="verifyActiveListings" class="flex items-center gap-1.5">
                                <i class="ph ph-arrows-counter-clockwise text-base"></i>
                                Cek & Sinkronisasi Link Tutup
                            </span>
                            <span wire:loading wire:target="verifyActiveListings" class="flex items-center gap-1.5">
                                <span class="w-3 h-3 border-2 border-zinc-500 border-t-transparent rounded-full animate-spin"></span>
                                Sinkronisasi Berjalan...
                            </span>
                        </button>
                    </div>

                    <!-- 3. Manual Scraper Ingestion Card -->
                    <div class="bg-white border border-zinc-200 rounded-lg p-5 flex flex-col justify-between shadow-3xs space-y-4">
                        <div>
                            <div class="flex items-center gap-2 pb-2.5 border-b border-zinc-100">
                                <div class="w-6 h-6 rounded bg-zinc-900 text-white flex items-center justify-center">
                                    <i class="ph-bold ph-play text-[10px]"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-zinc-900 tracking-tight">Manual Scraper Ingestion</h3>
                                    <p class="text-[9px] text-zinc-400">Trigger penelusuran tautan semua platform</p>
                                </div>
                            </div>
                            
                            <div class="mt-3.5 space-y-2 text-[10px] font-mono">
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">Metode Ingesti:</span>
                                    <span class="font-bold text-zinc-800">Multi-Keyword Discovery</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">Tingkat Penjelajahan:</span>
                                    <span class="font-bold text-zinc-800">Halaman 1 (Semua Sektor)</span>
                                </div>
                            </div>
                        </div>

                        <button type="button" 
                                wire:click="triggerManualCrawl" 
                                wire:loading.attr="disabled"
                                class="w-full bg-zinc-950 hover:bg-zinc-900 text-white font-semibold text-xs rounded-md py-2 transition-all duration-150 shadow-3xs flex items-center justify-center gap-1.5">
                            <span wire:loading.remove wire:target="triggerManualCrawl" class="flex items-center gap-1.5">
                                <i class="ph ph-lightning text-base"></i>
                                Jalankan Scrape Manual
                            </span>
                            <span wire:loading wire:target="triggerManualCrawl" class="flex items-center gap-1.5">
                                <span class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                Memicu Scraper...
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Bottom Config Block: Throttling Rules (Left) and Proxy Pool (Right) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">
                    <!-- Left: Throttling Rules Configuration (1/3) -->
                    <div class="lg:col-span-1 bg-white border border-zinc-200/80 rounded-lg p-4 flex flex-col justify-between shadow-3xs">
                        <div>
                            <div class="flex items-center gap-2 pb-3 mb-4 border-b border-zinc-100">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                                    <i class="ph-bold ph-sliders text-xs"></i>
                                </div>
                                <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Throttling Rules</h2>
                            </div>

                            <!-- Domain Selector Tabs -->
                            <div class="space-y-1 mb-5">
                                @foreach ($sources as $source)
                                    <button type="button" 
                                            wire:click="editSource({{ $source['id'] }})"
                                            class="w-full text-left px-3 py-2.5 rounded-md text-xs flex items-center justify-between border transition-all duration-150 {{ $editingSourceId === $source['id'] ? 'bg-zinc-900 border-zinc-900 text-white font-medium shadow-3xs' : 'bg-zinc-50/50 border-zinc-200/80 text-zinc-600 hover:bg-zinc-100/50 hover:text-zinc-900' }}">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $source['is_active'] ? 'bg-emerald-500 animate-pulse' : 'bg-zinc-300' }}"></span>
                                            <span class="tracking-tight">{{ $source['name'] }}</span>
                                        </div>
                                        <span class="text-[9px] font-mono opacity-80 uppercase">{{ str_replace('.com', '', str_replace('.co.id', '', $source['target_domain'])) }}</span>
                                    </button>
                                @endforeach
                            </div>

                            @if ($editingSourceId)
                                <!-- Settings Input Form -->
                                <form wire:submit.prevent="saveSourceSettings" class="space-y-4">
                                    
                                    <!-- Toggle Switch -->
                                    <div class="flex items-center justify-between bg-zinc-50/50 border border-zinc-200/80 rounded-md p-3">
                                        <div>
                                            <label class="text-xs font-semibold text-zinc-900 tracking-tight">Active Crawler Status</label>
                                            <p class="text-[10px] text-zinc-400">Run cron cycles for this target</p>
                                        </div>
                                        <button type="button" 
                                                wire:click="$set('is_active', {{ $is_active ? 'false' : 'true' }})"
                                                class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden {{ $is_active ? 'bg-zinc-950' : 'bg-zinc-200' }}">
                                            <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition duration-200 ease-in-out {{ $is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                        </button>
                                    </div>

                                    <!-- Politeness Delay -->
                                    <div>
                                        <div class="flex justify-between items-baseline mb-1">
                                            <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Politeness Delay</label>
                                            <span class="text-xs font-mono font-semibold text-zinc-955">{{ $delay_between_requests_ms }}ms</span>
                                        </div>
                                        <input type="range" min="0" max="10000" step="250" 
                                               wire:model.live="delay_between_requests_ms"
                                               class="w-full accent-zinc-955 bg-zinc-150 h-1.5 rounded-lg cursor-pointer">
                                    </div>

                                    <!-- Concurrency Limit -->
                                    <div>
                                        <div class="flex justify-between items-baseline mb-1">
                                            <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Concurrency Limit</label>
                                            <span class="text-xs font-mono font-semibold text-zinc-955">{{ $max_concurrency }} parallel</span>
                                        </div>
                                        <input type="range" min="1" max="15" step="1" 
                                               wire:model.live="max_concurrency"
                                               class="w-full accent-zinc-955 bg-zinc-150 h-1.5 rounded-lg cursor-pointer">
                                    </div>

                                    <!-- Schedule Interval -->
                                    <div>
                                        <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Crawl Frequency Interval</label>
                                        <div class="relative">
                                            <input type="number" 
                                                   wire:model="frequency_minutes"
                                                   class="w-full text-xs bg-zinc-50/50 border border-zinc-200 rounded-md px-3 py-2 text-zinc-900 font-mono focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 focus:outline-hidden"
                                                   required>
                                            <span class="absolute right-3 top-2 text-[10px] font-mono text-zinc-400">minutes</span>
                                        </div>
                                    </div>

                                    <button type="submit" 
                                            class="w-full bg-zinc-950 hover:bg-zinc-900 text-white font-medium text-xs rounded-md py-2 transition-all duration-150 shadow-3xs flex items-center justify-center gap-1.5">
                                        <i class="ph ph-check"></i>
                                        Save Config Settings
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Proxy Pool Monitor (2/3 width) -->
                    <div class="lg:col-span-2 bg-white border border-zinc-200/80 rounded-lg p-4 flex flex-col justify-between shadow-3xs">
                        <div>
                            <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                                        <i class="ph-bold ph-shield-check text-xs"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Proxy Pool Monitor</h2>
                                        <p class="text-[9px] text-zinc-400">Status rotasi IP bypass bot-blocker</p>
                                    </div>
                                </div>
                                
                                <button type="button" 
                                        wire:click="checkProxyHealth" 
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1 bg-zinc-950 hover:bg-zinc-900 text-white font-semibold text-[10px] rounded px-2.5 py-1.5 transition-all duration-150 shadow-3xs disabled:opacity-50">
                                    <span wire:loading.remove wire:target="checkProxyHealth" class="flex items-center gap-1">
                                        <i class="ph ph-heartbeat"></i> Ping Proxy
                                    </span>
                                    <span wire:loading wire:target="checkProxyHealth" class="flex items-center gap-1">
                                        <span class="w-2.5 h-2.5 border border-white border-t-transparent rounded-full animate-spin"></span>
                                        Memverifikasi...
                                    </span>
                                </button>
                            </div>

                            <!-- Proxy Cards Grid -->
                            @if (count($proxyStatusList) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($proxyStatusList as $index => $proxyItem)
                                        <div class="border rounded-md p-3 flex flex-col justify-between space-y-2 shadow-3xs {{ $proxyItem['class'] }}">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[8px] font-mono font-bold uppercase tracking-wider text-zinc-400">PROXY #{{ $index + 1 }}</span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[7px] font-mono font-black border border-current">
                                                    {{ $proxyItem['status'] }}
                                                </span>
                                            </div>
                                            <div class="space-y-1 text-[9px] font-mono">
                                                <span class="text-zinc-800 truncate block">{{ parse_url($proxyItem['address'], PHP_URL_HOST) ?? $proxyItem['address'] }}</span>
                                                <span class="text-zinc-500 font-bold block">IP: {{ $proxyItem['ip'] }}</span>
                                            </div>
                                            <div class="flex justify-between border-t border-zinc-200/50 pt-1.5 text-[8px] font-mono">
                                                <span>Latency:</span>
                                                <span class="font-bold text-zinc-850">{{ $proxyItem['latency'] > 0 ? $proxyItem['latency'] . ' ms' : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                @if ($isPingingProxies)
                                    <div class="flex items-center justify-center p-8 text-center border border-dashed rounded bg-zinc-50 border-zinc-200/80">
                                        <span class="w-5 h-5 border-2 border-zinc-950 border-t-transparent rounded-full animate-spin mr-2"></span>
                                        <p class="text-[10px] text-zinc-650">Menguji latency proxy pool...</p>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center p-8 text-center border border-dashed rounded bg-zinc-50 border-zinc-200/80 border-dashed">
                                        <p class="text-[10px] font-semibold text-zinc-500 mb-1">Status Proxy Belum Diuji</p>
                                        <p class="text-[9px] text-zinc-400">Tekan tombol ping untuk melakukan verifikasi status IP Pool.</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($activeTab === 'target')
            <!-- TAB 3: TARGET-DRIVEN INGESTION -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch animate-fade-in text-zinc-900">
                <!-- Request Form Card -->
                <div class="lg:col-span-1 bg-white border border-zinc-200/80 rounded-lg p-4 shadow-3xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 pb-3 mb-4 border-b border-zinc-100">
                            <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                                <i class="ph-bold ph-crosshair text-xs"></i>
                            </div>
                            <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Scraping Target Goal</h2>
                        </div>

                        <form wire:submit.prevent="runTargetedScraping" class="space-y-4">
                            <!-- Sector Select -->
                            <div>
                                <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Pilih Sektor Ekonomi</label>
                                <select wire:model.live="targetSector" 
                                        class="w-full text-xs bg-zinc-50/50 border border-zinc-200 rounded-md px-3 py-2 text-zinc-900 focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 focus:outline-hidden"
                                        required>
                                    <option value="">-- Pilih Sektor --</option>
                                    @foreach (\App\Helpers\CategoryHelper::getSektors() as $sektor)
                                        <option value="{{ $sektor }}">{{ $sektor }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Major Select -->
                            <div>
                                <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Pilih Rumpun Jurusan</label>
                                <select wire:model="targetMajor" 
                                        class="w-full text-xs bg-zinc-50/50 border border-zinc-200 rounded-md px-3 py-2 text-zinc-900 focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 focus:outline-hidden"
                                        {{ empty($targetSector) ? 'disabled' : '' }}
                                        required>
                                    <option value="">-- Pilih Jurusan --</option>
                                    @foreach ($this->targetMajorsList as $majorOption)
                                        <option value="{{ $majorOption }}">{{ $majorOption }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quantity Limit -->
                            <div>
                                <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Target Jumlah Loker Baru</label>
                                <div class="relative">
                                    <input type="number" 
                                           wire:model="targetJobsCount"
                                           min="5" max="300"
                                           class="w-full text-xs bg-zinc-50/50 border border-zinc-200 rounded-md px-3 py-2 text-zinc-900 font-mono focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 focus:outline-hidden"
                                           required>
                                    <span class="absolute right-3 top-2 text-[10px] font-mono text-zinc-400">loker baru</span>
                                </div>
                                <span class="text-[9px] text-zinc-400 mt-1 block">Scraper akan berhenti secara otomatis saat database bertambah sebanyak jumlah target di atas.</span>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-zinc-950 hover:bg-zinc-900 text-white font-medium text-xs rounded-md py-2 transition-all duration-150 shadow-3xs flex items-center justify-center gap-1.5">
                                <i class="ph ph-rocket-launch"></i>
                                Jalankan Misi Targeted Ingestion
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 pt-4 border-t border-zinc-150 text-[10px] text-zinc-400 space-y-1.5 font-mono leading-tight">
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Misi berjalan otomatis di background.</span>
                        </div>
                    </div>
                </div>

                <!-- Live Progress Dashboard -->
                <div class="lg:col-span-2 bg-white border border-zinc-200/80 rounded-lg p-4 shadow-3xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                                    <i class="ph-bold ph-activity text-xs"></i>
                                </div>
                                <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Misi Aktif & Status Real-Time</h2>
                            </div>
                        </div>

                        @if ($targetedProgress)
                            <div class="space-y-6">
                                <!-- Progress Info Card -->
                                <div class="bg-zinc-50 border border-zinc-200 rounded-lg p-5 space-y-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-mono font-bold {{ $targetedProgress['status'] === 'RUNNING' ? 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                                STATUS: {{ $targetedProgress['status'] }}
                                            </span>
                                            <h3 class="text-sm font-semibold text-zinc-900 mt-1.5">{{ $targetedProgress['major'] }}</h3>
                                            <p class="text-[10px] text-zinc-450">{{ $targetedProgress['sector'] }}</p>
                                        </div>
                                        
                                        @if ($targetedProgress['status'] === 'RUNNING')
                                            <button type="button" 
                                                    wire:click="cancelTargetedScraping"
                                                    class="text-xs text-red-600 hover:text-red-800 font-medium px-2.5 py-1.5 border border-red-200 rounded-md hover:bg-red-50 transition-colors">
                                                Batalkan Misi
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Progress Bar -->
                                    <div>
                                        <div class="flex justify-between text-xs font-semibold mb-1">
                                            <span class="text-zinc-600">Perkembangan Ingesti</span>
                                            <span class="text-zinc-900 font-mono">{{ $targetedProgress['current'] }} / {{ $targetedProgress['target'] }} Loker Baru ({{ round(($targetedProgress['current'] / $targetedProgress['target']) * 100) }}%)</span>
                                        </div>
                                        <div class="w-full bg-zinc-200 h-2 rounded-full overflow-hidden">
                                            <div class="bg-zinc-950 h-full rounded-full transition-all duration-300" style="width: {{ min(100, ($targetedProgress['current'] / $targetedProgress['target']) * 100) }}%"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 text-[10px] font-mono text-zinc-450 border-t border-zinc-150 pt-3">
                                        <div>Mulai: {{ $targetedProgress['started_at'] }}</div>
                                        <div>Selesai: {{ $targetedProgress['completed_at'] ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="bg-zinc-50 border border-zinc-150 rounded-lg p-5">
                                    <h4 class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2.5">Panduan Prosedur Targeted Ingestion</h4>
                                    <ul class="text-[10px] text-zinc-500 space-y-1.5 leading-relaxed list-disc list-inside">
                                        <li>Background worker akan secara khusus memprioritaskan antrean keyword relasi rumpun jurusan pilihan.</li>
                                        <li>Loker yang di-update (sudah terdaftar di DB sebelumnya) tidak dihitung dalam pertambahan kuota agar data yang masuk murni 100% baru.</li>
                                        <li>Anda dapat memantau detil lowongan baru yang masuk secara live pada box <strong>Live Log Streamer Console</strong> di halaman depan.</li>
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="bg-zinc-50 border border-zinc-150 rounded-lg p-12 text-center flex flex-col items-center justify-center border-dashed h-full min-h-[300px]">
                                <i class="ph ph-crosshair text-zinc-300 text-4xl mb-3"></i>
                                <p class="text-xs font-semibold text-zinc-600 mb-1">Belum Ada Misi Targeted Ingestion</p>
                                <p class="text-[10px] text-zinc-400 max-w-sm">Tentukan Sektor Ekonomi, Rumpun Jurusan, dan kuota target loker baru pada form kiri, lalu klik jalankan untuk memicu background crawling khusus.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if ($activeTab === 'sandbox')
            <!-- TAB 4: SANDBOX & CSS SELECTOR TESTER -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch animate-fade-in text-zinc-900">
                <!-- Selectors Configuration Card -->
                <div class="lg:col-span-1 bg-white border border-zinc-200/80 rounded-lg p-4 shadow-3xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 pb-3 mb-4 border-b border-zinc-100">
                            <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                                <i class="ph-bold ph-terminal-window text-xs"></i>
                            </div>
                            <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">DOM Selectors Configuration</h2>
                        </div>

                        <form wire:submit.prevent="runVisualSandboxTest" class="space-y-4">
                            <!-- Target URL -->
                            <div>
                                <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Target URL Lowongan Detail</label>
                                <input type="url" 
                                       wire:model="sandboxUrl" 
                                       placeholder="https://example.com/jobs/view/123" 
                                       class="w-full text-xs bg-zinc-50/50 border border-zinc-200 rounded-md px-3 py-2 text-zinc-900 focus:bg-white focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 focus:outline-hidden"
                                       required>
                                @error('sandboxUrl')
                                    <p class="text-[9px] text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Title Selector -->
                            <div>
                                <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Selector CSS Judul Lowongan</label>
                                <input type="text" 
                                       wire:model="sandboxTitleSelector" 
                                       placeholder="h1.job-title atau .title-class" 
                                       class="w-full text-xs bg-zinc-50/50 border border-zinc-200 rounded-md px-3 py-2 text-zinc-900 font-mono focus:bg-white focus:ring-1 focus:ring-zinc-955 focus:border-zinc-955 focus:outline-hidden"
                                       required>
                                @error('sandboxTitleSelector')
                                    <p class="text-[9px] text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Company Selector -->
                            <div>
                                <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Selector CSS Perusahaan (Opsional)</label>
                                <input type="text" 
                                       wire:model="sandboxCompanySelector" 
                                       placeholder="span.company atau .company-name" 
                                       class="w-full text-xs bg-zinc-50/50 border border-zinc-200 rounded-md px-3 py-2 text-zinc-900 font-mono focus:bg-white focus:ring-1 focus:ring-zinc-955 focus:border-zinc-955 focus:outline-hidden">
                            </div>

                            <!-- Body Selector -->
                            <div>
                                <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Selector CSS Deskripsi Lowongan (Opsional)</label>
                                <input type="text" 
                                       wire:model="sandboxBodySelector" 
                                       placeholder="div.job-description atau #details" 
                                       class="w-full text-xs bg-zinc-50/50 border border-zinc-200 rounded-md px-3 py-2 text-zinc-900 font-mono focus:bg-white focus:ring-1 focus:ring-zinc-955 focus:border-zinc-955 focus:outline-hidden">
                            </div>

                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="w-full bg-zinc-950 hover:bg-zinc-900 disabled:bg-zinc-400 text-white font-medium text-xs rounded-md py-2 transition-all duration-150 shadow-3xs flex items-center justify-center gap-1.5">
                                <span wire:loading.remove wire:target="runVisualSandboxTest">Uji Coba Selektor CSS</span>
                                <span wire:loading wire:target="runVisualSandboxTest" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 pt-4 border-t border-zinc-150 text-[10px] text-zinc-400 space-y-1.5 font-mono leading-tight">
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-zinc-300"></span>
                            <span>Mendukung rendering Puppeteer jika diaktifkan.</span>
                        </div>
                    </div>
                </div>

                <!-- Test Preview Cards & Logs Output -->
                <div class="lg:col-span-2 bg-white border border-zinc-200/80 rounded-lg p-4 shadow-3xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                                    <i class="ph-bold ph-eye text-xs"></i>
                                </div>
                                <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Hasil Ekstraksi & Pratinjau DOM</h2>
                            </div>
                        </div>

                        <!-- Sandbox Console Logs -->
                        <div class="mb-5">
                            <p class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">Logs Jalur Sandbox</p>
                            @if (count($sandboxLogs) > 0)
                                <div class="bg-[#121214] text-[#a1a1aa] border border-zinc-800 p-4 rounded-lg font-mono text-[10px] leading-relaxed shadow-inner max-h-[150px] overflow-y-auto space-y-1.5 custom-scrollbar">
                                    @foreach ($sandboxLogs as $log)
                                        <div class="whitespace-pre-wrap tracking-tight">{{ $log }}</div>
                                    @endforeach
                                    
                                    @if ($sandboxIsTesting)
                                        <div class="flex items-center gap-1.5 text-zinc-550 mt-1 animate-pulse">
                                            <span class="w-1.5 h-1.5 bg-zinc-500 rounded-full animate-ping"></span>
                                            <span>Mengekstrak DOM dengan selectors...</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="bg-zinc-50 border border-zinc-150 rounded-lg p-8 text-center flex flex-col items-center justify-center border-dashed">
                                    <i class="ph ph-code text-zinc-400 text-2xl mb-2"></i>
                                    <p class="text-xs font-semibold text-zinc-650 mb-0.5">Logs pengujian kosong</p>
                                    <p class="text-[10px] text-zinc-400 max-w-sm">Masukkan URL dan selector CSS kustom Anda di sisi kiri, lalu klik uji coba untuk melihat hasil parsing secara detail.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Extracted Payload Preview -->
                        @if ($sandboxExtractedPayload)
                            <div class="border border-zinc-200 rounded-lg p-4 bg-zinc-50/50 space-y-3.5 shadow-3xs">
                                <div class="flex items-center justify-between border-b border-zinc-200 pb-2.5">
                                    <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Payload Terurai (Extracted)</span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-black {{ $sandboxTestResultStatus === 'SUCCESS' ? 'bg-emerald-50 text-emerald-700 border border-emerald-205' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        [{{ $sandboxTestResultStatus }}]
                                    </span>
                                </div>

                                <div class="space-y-3 text-xs tracking-tight">
                                    <div>
                                        <span class="text-[9px] text-zinc-400 font-mono block uppercase tracking-wider mb-0.5">Judul Lowongan</span>
                                        <span class="font-semibold text-zinc-900 text-sm">{{ $sandboxExtractedPayload['title'] }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[9px] text-zinc-400 font-mono block uppercase tracking-wider mb-0.5">Nama Perusahaan</span>
                                        <span class="font-semibold text-zinc-900">{{ $sandboxExtractedPayload['company'] }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[9px] text-zinc-400 font-mono block uppercase tracking-wider mb-0.5">Potongan Deskripsi Loker</span>
                                        <div class="bg-white border border-zinc-200 rounded p-2.5 font-mono text-[9px] text-zinc-650 max-h-[100px] overflow-y-auto leading-relaxed">
                                            {{ $sandboxExtractedPayload['description'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-4 border-t border-zinc-100 text-[10px] font-mono text-zinc-400">
                        Visual selector builder bermanfaat untuk memotong bug perubahan struktur HTML pada platform loker eksternal.
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Chart.js CDN & Setup Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const metrics = @json($platformMetrics);
            const ctx = document.getElementById('scraperPerformanceChart');
            if (ctx) {
                const labels = metrics.map(m => m.name);
                const successData = metrics.map(m => m.success);
                const failData = metrics.map(m => m.fail);

                window.scraperChart = new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Sukses',
                                data: successData,
                                backgroundColor: '#10b981', // emerald-500
                                borderRadius: 4,
                                maxBarThickness: 24,
                            },
                            {
                                label: 'Gagal / Diblokir',
                                data: failData,
                                backgroundColor: '#f43f5e', // rose-500
                                borderRadius: 4,
                                maxBarThickness: 24,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                align: 'end',
                                labels: {
                                    boxWidth: 8,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 9,
                                        family: 'Plus Jakarta Sans',
                                        weight: 600
                                    },
                                    color: '#71717a'
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                grid: { display: false },
                                ticks: {
                                    font: { size: 9, family: 'Plus Jakarta Sans', weight: 600 },
                                    color: '#71717a'
                                }
                            },
                            y: {
                                stacked: true,
                                grid: { color: '#f4f4f5', drawTicks: false },
                                border: { dash: [4, 4] },
                                ticks: {
                                    font: { size: 9, family: 'Plus Jakarta Sans', weight: 600 },
                                    color: '#71717a'
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</div>
