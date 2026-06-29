<div wire:poll.5s class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 space-y-5 pb-10">
    
    {{-- Alert Messages --}}
    @if (session()->has('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-center gap-2.5 shadow-3xs animate-fade-in">
            <i class="ph-bold ph-check-circle text-base text-emerald-650 shrink-0"></i>
            <p class="text-xs font-semibold">{{ session('success') }}</p>
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
        <div class="flex items-center gap-2.5 flex-wrap">
            <!-- Verify Active Listings Link Check -->
            <button type="button" 
                    wire:click="verifyActiveListings" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 bg-white hover:bg-zinc-50 border border-zinc-200 text-zinc-700 font-medium text-xs rounded-md px-3 py-1.5 transition-all duration-150 disabled:opacity-50">
                <span wire:loading.remove wire:target="verifyActiveListings" class="flex items-center gap-1.5">
                    <i class="ph ph-shield-warning text-sm"></i>
                    Cek & Sinkronisasi Link Tutup
                </span>
                <span wire:loading wire:target="verifyActiveListings" class="flex items-center gap-1.5">
                    <span class="w-3 h-3 border-2 border-zinc-500 border-t-transparent rounded-full animate-spin"></span>
                    Mensinkronisasi...
                </span>
            </button>

            <!-- Trigger Manual Scraper Ingestion -->
            <button type="button" 
                    wire:click="triggerManualCrawl" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 bg-zinc-950 hover:bg-zinc-900 text-white font-medium text-xs rounded-md px-3 py-1.5 transition-all duration-150 disabled:opacity-50">
                <span wire:loading.remove wire:target="triggerManualCrawl" class="flex items-center gap-1.5">
                    <i class="ph ph-play text-sm"></i>
                    Jalankan Scraper Manual
                </span>
                <span wire:loading wire:target="triggerManualCrawl" class="flex items-center gap-1.5">
                    <span class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    Memicu Scraper...
                </span>
            </button>

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

        {{-- Active Sources --}}
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

    <!-- Main Content Workspace Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">
        
        <!-- Left Side: Source Configuration & Throttling (1/3) -->
        <div class="lg:col-span-1 bg-white border border-zinc-200/80 rounded-lg p-4 flex flex-col justify-between h-full shadow-3xs">
            <div>
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-zinc-100">
                    <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-600">
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
                    <form wire:submit.prevent="saveSourceSettings" class="space-y-5">
                        
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
                                <span class="text-xs font-mono font-semibold text-zinc-950">{{ $delay_between_requests_ms }}ms</span>
                            </div>
                            <input type="range" min="0" max="10000" step="250" 
                                   wire:model.live="delay_between_requests_ms"
                                   class="w-full accent-zinc-950 bg-zinc-150 h-1.5 rounded-lg cursor-pointer">
                            <span class="text-[10px] text-zinc-400 mt-1 block">Cooldown delay between detail listing queries</span>
                        </div>

                        <!-- Concurrency Limit -->
                        <div>
                            <div class="flex justify-between items-baseline mb-1">
                                <label class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Concurrency Limit</label>
                                <span class="text-xs font-mono font-semibold text-zinc-950">{{ $max_concurrency }} parallel</span>
                            </div>
                            <input type="range" min="1" max="15" step="1" 
                                   wire:model.live="max_concurrency"
                                   class="w-full accent-zinc-950 bg-zinc-150 h-1.5 rounded-lg cursor-pointer">
                            <span class="text-[10px] text-zinc-400 mt-1 block">Maximum concurrent Chromium worker containers</span>
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
                            <span class="text-[10px] text-zinc-400 mt-1 block">Interval cooldown to scan seed URL updates</span>
                        </div>

                        <button type="submit" 
                                class="w-full bg-zinc-950 hover:bg-zinc-900 text-white font-medium text-xs rounded-md py-2 transition-all duration-150 shadow-3xs flex items-center justify-center gap-1.5">
                            <i class="ph ph-check"></i>
                            Save Config Settings
                        </button>
                    </form>
                @endif
            </div>

            <!-- Dashboard Notes -->
            <div class="mt-6 pt-4 border-t border-zinc-150 text-[10px] text-zinc-400 space-y-1.5 font-mono leading-tight">
                <div class="flex items-center gap-1.5">
                    <span class="w-1 h-1 rounded-full bg-zinc-300"></span>
                    <span>Throttles sync to Redis store instantly.</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-1 h-1 rounded-full bg-zinc-300"></span>
                    <span>Auto-backoff triggers above 15% error rate.</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Test Ping Bot Sandbox (2/3) -->
        <div class="lg:col-span-2 bg-white border border-zinc-200/80 rounded-lg p-4 flex flex-col justify-between h-full shadow-3xs">
            <div>
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                            <i class="ph-bold ph-terminal-window text-xs"></i>
                        </div>
                        <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Test Ping Bot Sandbox</h2>
                    </div>
                </div>

                <!-- Sandbox Form -->
                <form wire:submit.prevent="runTestSandbox" class="space-y-4 mb-5">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <div class="flex-1">
                            <input type="url" 
                                   wire:model="testUrl"
                                   placeholder="Paste raw target Job URL (e.g., https://id.linkedin.com/jobs/view/...)" 
                                   class="w-full text-xs bg-zinc-50/50 border border-zinc-200 rounded-md px-3 py-2 text-zinc-900 focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 focus:outline-hidden"
                                   required>
                        </div>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="bg-zinc-950 hover:bg-zinc-900 disabled:bg-zinc-400 text-white font-medium text-xs rounded-md px-4 py-2 transition-all duration-150 flex items-center justify-center gap-1.5 shrink-0 shadow-3xs">
                            <span wire:loading.remove>Run Sandbox</span>
                            <span wire:loading class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        </button>
                    </div>
                    @error('testUrl')
                        <p class="text-[10px] text-red-600">{{ $message }}</p>
                    @enderror
                </form>

                <!-- Sandbox Logs Console -->
                <div class="mb-5">
                    <p class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider mb-2">Terminal Output Logs</p>
                    @if (count($logs) > 0)
                        <div class="bg-[#121214] text-[#a1a1aa] border border-zinc-800 p-4 rounded-lg font-mono text-[10px] leading-relaxed shadow-inner max-h-[170px] overflow-y-auto space-y-1.5 custom-scrollbar">
                            @foreach ($logs as $log)
                                <div class="whitespace-pre-wrap tracking-tight">{{ $log }}</div>
                            @endforeach
                            
                            @if ($isTesting)
                                <div class="flex items-center gap-1.5 text-zinc-500 mt-1 animate-pulse">
                                    <span class="w-1.5 h-1.5 bg-zinc-500 rounded-full animate-ping"></span>
                                    <span>Spinning up residential proxy session...</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-zinc-50 border border-zinc-150 rounded-lg p-10 text-center flex flex-col items-center justify-center border-dashed">
                            <i class="ph ph-terminal text-zinc-400 text-3xl mb-2.5"></i>
                            <p class="text-xs font-semibold text-zinc-600 mb-0.5">Console logs await execution</p>
                            <p class="text-[10px] text-zinc-400 max-w-sm">Paste a live URL from LinkedIn, JobStreet, or Kalibrr to run validation scripts, proxy routing, and extraction schemas.</p>
                        </div>
                    @endif
                </div>

                <!-- Extracted Summary Payload Deck -->
                @if ($extractedPayload)
                    <div class="border border-zinc-200 rounded-lg p-4 bg-zinc-50/50 space-y-3.5 shadow-3xs">
                        <div class="flex items-center justify-between border-b border-zinc-200 pb-2.5">
                            <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Extracted Payload</span>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-black {{ $testResultStatus === 'SUCCESS' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                    [{{ $testResultStatus }}]
                                </span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-mono font-black {{ $testJobStatus === 'ACTIVE' ? 'bg-emerald-500 text-white' : 'bg-zinc-700 text-white' }}">
                                    [JOB STATUS: {{ $testJobStatus }}]
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-xs tracking-tight">
                            <div>
                                <span class="text-[10px] text-zinc-400 font-mono block uppercase tracking-wider mb-0.5">Job Title</span>
                                <span class="font-semibold text-zinc-900">{{ $extractedPayload['title'] }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-zinc-400 font-mono block uppercase tracking-wider mb-0.5">Target Portal</span>
                                <span class="font-semibold text-zinc-900">{{ $extractedPayload['company'] }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-zinc-400 font-mono block uppercase tracking-wider mb-0.5">Routed Proxy</span>
                                <span class="font-mono text-zinc-800 font-semibold">{{ $testProxyIp }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-zinc-400 font-mono block uppercase tracking-wider mb-0.5">Expired Footprints</span>
                                <span class="font-semibold text-zinc-900 uppercase">
                                    {{ $extractedPayload['closing_indicator_found'] ? 'FOUND / CLOSED' : 'NOT FOUND / ACTIVE' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Footer / Docker container state indicators -->
            <div class="mt-6 pt-4 border-t border-zinc-100 flex flex-col sm:flex-row items-center justify-between text-[10px] font-mono text-zinc-400 gap-2">
                <div class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span>Docker Container: <strong>node-test-01</strong></span>
                </div>
                <span>Session Routed Proxy: <strong>{{ $testProxyIp ?: 'Inactive' }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Live Logs & Performance Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch mt-2">
        <!-- Platform Performance Charts -->
        <div class="bg-white border border-zinc-200/80 rounded-lg p-4 flex flex-col justify-between shadow-3xs" wire:ignore>
            <div>
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-zinc-100">
                    <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                        <i class="ph-bold ph-chart-bar text-xs"></i>
                    </div>
                    <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Metrik Grafik Performa Platform</h2>
                </div>
                
                <div class="relative h-[250px] w-full">
                    <canvas id="scraperPerformanceChart"></canvas>
                </div>
            </div>
            
            <p class="text-[9px] font-mono text-zinc-400 mt-3">Statistik keberhasilan scraping dan penyaringan loker dari database log.</p>
        </div>

        <!-- Live Log Streamer Terminal -->
        <div class="bg-white border border-zinc-200/80 rounded-lg p-4 flex flex-col justify-between shadow-3xs">
            <div>
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-650">
                            <i class="ph-bold ph-terminal text-xs"></i>
                        </div>
                        <h2 class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Live Log Streamer Console</h2>
                    </div>
                    
                    <button type="button" 
                            wire:click="clearLiveLogs"
                            class="text-[9px] font-mono font-bold text-rose-600 hover:text-rose-800 transition-colors uppercase tracking-wider flex items-center gap-1 focus:outline-hidden">
                        <i class="ph ph-trash"></i> Bersihkan Log
                    </button>
                </div>

                @if (count($liveLogs) > 0)
                    <div class="bg-[#121214] text-[#e4e4e7] border border-zinc-800 p-4 rounded-lg font-mono text-[10px] leading-relaxed shadow-inner max-h-[220px] overflow-y-auto space-y-1.5 custom-scrollbar">
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
                    <div class="bg-zinc-50 border border-zinc-150 rounded-lg p-10 text-center flex flex-col items-center justify-center border-dashed max-h-[220px] h-full">
                        <i class="ph ph-activity text-zinc-400 text-3xl mb-2.5"></i>
                        <p class="text-xs font-semibold text-zinc-600 mb-0.5">Scraper Engine logs are empty</p>
                        <p class="text-[10px] text-zinc-400 max-w-sm">No live background crawler activity has been recorded in cache yet. Logs will stream here dynamically during execution.</p>
                    </div>
                @endif
            </div>
            
            <p class="text-[9px] font-mono text-zinc-400 mt-3">Konsol terminal diperbarui otomatis setiap siklus polling dashboard (5 detik).</p>
        </div>
    </div>

    <!-- Chart.js CDN & Setup Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const metrics = @json($platformMetrics);
            const ctx = document.getElementById('scraperPerformanceChart').getContext('2d');
            
            const labels = metrics.map(m => m.name);
            const successData = metrics.map(m => m.success);
            const failData = metrics.map(m => m.fail);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Sukses',
                            data: successData,
                            backgroundColor: '#10b981', // emerald-500
                            borderRadius: 4,
                        },
                        {
                            label: 'Gagal / Diblokir',
                            data: failData,
                            backgroundColor: '#f43f5e', // rose-500
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
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
                            grid: { color: '#f4f4f5' },
                            ticks: {
                                stepSize: 10,
                                font: { size: 9, family: 'Plus Jakarta Sans', weight: 600 },
                                color: '#71717a'
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>

