<div wire:poll.visible.5s class="admin-operations admin-scraper-workspace w-full">

    {{-- Alert Messages --}}
    @if (session()->has('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-2.5 text-xs">
            <i class="ph-bold ph-check-circle text-base text-emerald-600 shrink-0"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-3.5 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center gap-2.5 text-xs">
            <i class="ph-bold ph-x-circle text-base text-red-650 shrink-0"></i>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Header --}}
    <div class="ascr-welcome">
        <div>
            <h1>{{ __('Scraper Engine') }}</h1>
            <p>{{ __('Control web scraping schedules, job ingestion sources, selectors, and sandbox testing.') }}</p>
        </div>
        <div class="ascr-header-actions">
            <span class="ascr-refresh-badge">
                <span class="ascr-refresh-dot"></span>
                {{ __('Refreshes every 5s while visible') }}
            </span>
        </div>
    </div>

    {{-- Metric Overview Strip --}}
    <div class="ascr-metric-strip">
        {{-- Total Ingested --}}
        <div class="ascr-metric-card ascr-metric-card--total">
            <div class="ascr-metric-head">
                <span class="ascr-metric-label">
                    <i class="ph ph-database" aria-hidden="true"></i>
                    {{ __('Total Ingested Jobs') }}
                </span>
            </div>
            <strong>{{ number_format($stats['total_ingested']) }}</strong>
            <small>{{ __('Aggregated job listings across platforms') }}</small>
        </div>

        {{-- Scraped Today --}}
        <div class="ascr-metric-card ascr-metric-card--today">
            <div class="ascr-metric-head">
                <span class="ascr-metric-label">
                    <i class="ph ph-calendar-check" aria-hidden="true"></i>
                    {{ __('Scraped Today') }}
                </span>
            </div>
            <strong>{{ number_format($stats['jobs_scraped_today']) }}</strong>
            <small>{{ __('Ingested in the last 24 hours') }}</small>
        </div>

        {{-- Active Platforms --}}
        <div class="ascr-metric-card ascr-metric-card--platforms">
            <div class="ascr-metric-head">
                <span class="ascr-metric-label">
                    <i class="ph ph-globe" aria-hidden="true"></i>
                    {{ __('Active Platforms') }}
                </span>
            </div>
            <strong>{{ $stats['active_sources'] }} / {{ count($sources) }}</strong>
            <small>{{ __('LinkedIn, JobStreet, Kalibrr & others') }}</small>
        </div>

        {{-- Queue & Worker --}}
        <div class="ascr-metric-card ascr-metric-card--worker">
            <div class="ascr-metric-head">
                <span class="ascr-metric-label">
                    <i class="ph ph-cpu" aria-hidden="true"></i>
                    {{ __('Queue & Worker') }}
                </span>
            </div>
            <strong>{{ $stats['worker_status'] }}</strong>
            <small>{{ __(':value1 pending jobs · :value2 failed', ['value1' => ($stats['jobs_pending_in_queue']), 'value2' => ($stats['jobs_failed'])]) }}</small>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <nav class="ascr-tabs ops-tabs ops-scraper-tabs" aria-label="{{ __('Scraper sections') }}">
        @foreach(['dashboard'=>'Overview','control'=>'Engine controls','target'=>'Targeted search','sandbox'=>'Selector sandbox','reports'=>'Job reports'] as $key=>$label)
            <button type="button"
                    wire:click="$set('activeTab', '{{ $key }}')"
                    aria-current="{{ $activeTab === $key ? 'page' : 'false' }}"
                    class="ascr-tab {{ $activeTab === $key ? 'is-active' : '' }}">
                @switch($key)
                    @case('dashboard') <i class="ph ph-chart-bar" aria-hidden="true"></i> @break
                    @case('control') <i class="ph ph-sliders" aria-hidden="true"></i> @break
                    @case('target') <i class="ph ph-crosshair" aria-hidden="true"></i> @break
                    @case('sandbox') <i class="ph ph-code" aria-hidden="true"></i> @break
                    @case('reports') <i class="ph ph-flag" aria-hidden="true"></i> @break
                @endswitch
                <span>{{ __($label) }}</span>
                @if($key === 'reports' && count($reportedJobs) > 0)
                    <span class="ascr-tab-badge">{{ count($reportedJobs) }}</span>
                @endif
            </button>
        @endforeach
    </nav>

    <!-- Active Tab Workspace Container -->
    <div>
        @if ($activeTab === 'dashboard')
            <!-- TAB 1: OVERVIEW DASHBOARD & METRICS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch animate-fade-in text-zinc-900">
                <!-- Stacked Bar Performance Chart (50% width) -->
                <div class="lg:col-span-1 bg-white border border-[#e7e3e9]/80 rounded-lg p-4 flex flex-col gap-4  justify-between">
                    <div class="flex-1 flex flex-col">
                        <div class="flex items-center gap-2 pb-3 mb-4 border-b border-zinc-100 shrink-0">
                            <div class="w-6 h-6 rounded bg-zinc-50 border border-[#e7e3e9] flex items-center justify-center text-zinc-650">
                                <i class="ph-bold ph-chart-bar text-xs"></i>
                            </div>
                            <h2 class="text-xs font-sans font-medium text-zinc-500  ">{{ __('Metrik Grafik Performa Platform') }}</h2>
                        </div>

                        <div class="flex-1 min-h-[300px] relative w-full" wire:ignore>
                            <canvas id="scraperPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Live Log Streamer Console (50% width) -->
                <div class="lg:col-span-1 bg-white border border-[#e7e3e9]/80 rounded-lg p-4 flex flex-col justify-between ">
                    <div class="w-full">
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-[#e7e3e9] flex items-center justify-center text-zinc-650">
                                    <i class="ph-bold ph-terminal text-xs"></i>
                                </div>
                                <h2 class="text-xs font-sans font-medium text-zinc-500   font-semibold">{{ __('Live Logs Streamer') }}</h2>
                            </div>
                            <button type="button"
                                    wire:click="clearLiveLogs"
                                    class="text-[11px] font-sans font-medium text-rose-600 hover:text-rose-800 transition-colors   flex items-center gap-1 focus:outline-hidden">
                                <i class="ph ph-trash text-xs"></i> {{ __('Hapus Log') }}
                            </button>
                        </div>

                        @if (count($liveLogs) > 0)
                            <div class="border border-[#e7e3e9] rounded-2xl overflow-hidden shadow-2xs bg-white">
                                <div class="divide-y divide-zinc-100 overflow-y-auto h-[300px] custom-scrollbar">
                                    @foreach ($liveLogs as $log)
                                        @php
                                            $icon = 'ph-bold ph-info';
                                            $iconColor = 'text-blue-700 bg-blue-50 border-blue-100';
                                            if ($log['level'] === 'SUCCESS') {
                                                $icon = 'ph-bold ph-check-circle';
                                                $iconColor = 'text-emerald-700 bg-emerald-50 border-emerald-100';
                                            } elseif ($log['level'] === 'ERROR') {
                                                $icon = 'ph-bold ph-warning-octagon';
                                                $iconColor = 'text-rose-700 bg-rose-50 border-rose-100';
                                            } elseif ($log['level'] === 'WARNING') {
                                                $icon = 'ph-bold ph-warning';
                                                $iconColor = 'text-amber-700 bg-amber-50 border-amber-100';
                                            }
                                        @endphp
                                        <div class="p-3 flex items-start justify-between gap-3 hover:bg-zinc-50/40 transition-colors duration-150">
                                            <div class="flex items-start gap-2.5 min-w-0">
                                                <div class="w-6 h-6 rounded-lg flex items-center justify-center border shrink-0 {{ $iconColor }}">
                                                    <i class="{{ $icon }} text-xs"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-[11px] font-medium text-zinc-800 leading-tight break-words">{{ $log['message'] }}</p>
                                                    <span class="text-[11px] font-sans text-zinc-500 block mt-0.5">{{ __(':value1 Event', ['value1' => ($log['level'])]) }}</span>
                                                </div>
                                            </div>
                                            <span class="text-[11px] font-sans text-zinc-500 shrink-0 mt-0.5">{{ $log['timestamp'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="border border-[#e7e3e9] border-dashed rounded-2xl p-8 text-center flex flex-col items-center justify-center h-[300px] bg-zinc-50/50">
                                <div class="w-10 h-10 rounded-full bg-zinc-100 border border-[#e7e3e9] flex items-center justify-center text-zinc-500 mb-3 ">
                                    <i class="ph-bold ph-bell-simple-slash text-base"></i>
                                </div>
                                <h3 class="text-xs font-medium text-zinc-800 tracking-tight">{{ __('Tidak ada aktivitas baru') }}</h3>
                                <p class="text-[11px] text-zinc-500 max-w-[220px] mt-1 leading-normal">{{ __('Log aktivitas penarikan data akan mengalir secara live ke sini saat scraper sedang berjalan.') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="text-[11px] font-sans text-zinc-500 pt-3 border-t border-zinc-150 leading-tight mt-4 flex items-center justify-between">
                        <span>{{ __('Polling visual terupdate secara berkala setiap 5 detik.') }}</span>
                        <span class="text-zinc-500 font-medium   flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 "></span> {{ __('Live') }}</span>
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
                    <div class="bg-white border border-[#e7e3e9] rounded-lg p-5 flex flex-col justify-between  space-y-4">
                        <div>
                            <div class="flex items-center gap-2 pb-2.5 border-b border-zinc-100">
                                <div class="w-6 h-6 rounded bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700">
                                    <i class="ph-bold ph-cpu text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-medium text-zinc-900 tracking-tight">{{ __('Queue Worker Trigger') }}</h3>
                                    <p class="text-[11px] text-zinc-500">{{ __('Jalankan worker di latar belakang VM') }}</p>
                                </div>
                            </div>

                            <div class="mt-3.5 space-y-2 text-[11px] font-sans">
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">{{ __('Status Worker:') }}</span>
                                    <span class="font-medium text-emerald-600 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 "></span>
                                        {{ $stats['worker_status'] }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">{{ __('Jumlah Antrean:') }}</span>
                                    <span class="font-medium text-zinc-800">{{ __(':value1 jobs', ['value1' => ($stats['jobs_pending_in_queue'])]) }}</span>
                                </div>
                            </div>
                        </div>

                        <button type="button"
                                wire:click="startQueueWorker"
                                wire:loading.attr="disabled"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-md py-2 transition-all duration-150  flex items-center justify-center gap-1.5">
                            <span wire:loading.remove wire:target="startQueueWorker" class="flex items-center gap-1.5">
                                <i class="ph ph-play-circle text-base"></i>
                                {{ __('Jalankan Queue Worker') }}
                            </span>
                            <span wire:loading wire:target="startQueueWorker" class="flex items-center gap-1.5">
                                <span class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                {{ __('Memicu Worker...') }}
                            </span>
                        </button>
                    </div>

                    <!-- 2. Dead-Link Validator Card -->
                    <div class="bg-white border border-[#e7e3e9] rounded-lg p-5 flex flex-col justify-between  space-y-4">
                        <div>
                            <div class="flex items-center gap-2 pb-2.5 border-b border-zinc-100">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-[#e7e3e9] flex items-center justify-center text-zinc-650">
                                    <i class="ph-bold ph-shield-warning text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-medium text-zinc-900 tracking-tight">{{ __('Dead-Link Synchronizer') }}</h3>
                                    <p class="text-[11px] text-zinc-500">{{ __('Verifikasi status keaktifan URL lowongan') }}</p>
                                </div>
                            </div>

                            <div class="mt-3.5 space-y-2 text-[11px] font-sans">
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">{{ __('Metode Validasi:') }}</span>
                                    <span class="font-medium text-zinc-800">{{ __('404 / Footprint Check') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">{{ __('Target Validasi:') }}</span>
                                    <span class="font-medium text-zinc-800">{{ __('Loker Status \'active\'') }}</span>
                                </div>
                            </div>
                        </div>

                        <button type="button"
                                wire:click="verifyActiveListings"
                                wire:loading.attr="disabled"
                                class="w-full bg-white hover:bg-zinc-50 border border-zinc-250 text-zinc-700 font-semibold text-xs rounded-md py-2 transition-all duration-150 flex items-center justify-center gap-1.5 ">
                            <span wire:loading.remove wire:target="verifyActiveListings" class="flex items-center gap-1.5">
                                <i class="ph ph-arrows-counter-clockwise text-base"></i>
                                {{ __('Cek & Sinkronisasi Link Tutup') }}
                            </span>
                            <span wire:loading wire:target="verifyActiveListings" class="flex items-center gap-1.5">
                                <span class="w-3 h-3 border-2 border-zinc-500 border-t-transparent rounded-full animate-spin"></span>
                                {{ __('Sinkronisasi Berjalan...') }}
                            </span>
                        </button>
                    </div>

                    <!-- 3. Manual Scraper Ingestion Card -->
                    <div class="bg-white border border-[#e7e3e9] rounded-lg p-5 flex flex-col justify-between  space-y-4">
                        <div>
                            <div class="flex items-center gap-2 pb-2.5 border-b border-zinc-100">
                                <div class="w-6 h-6 rounded bg-zinc-900 text-white flex items-center justify-center">
                                    <i class="ph-bold ph-play text-[11px]"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-medium text-zinc-900 tracking-tight">{{ __('Manual Scraper Ingestion') }}</h3>
                                    <p class="text-[11px] text-zinc-500">{{ __('Trigger penelusuran tautan semua platform') }}</p>
                                </div>
                            </div>

                            <div class="mt-3.5 space-y-2 text-[11px] font-sans">
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">{{ __('Metode Ingesti:') }}</span>
                                    <span class="font-medium text-zinc-800">{{ __('Multi-Keyword Discovery') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-zinc-500">{{ __('Tingkat Penjelajahan:') }}</span>
                                    <span class="font-medium text-zinc-800">{{ __('Halaman 1 (Semua Sektor)') }}</span>
                                </div>
                            </div>
                        </div>

                        <button type="button"
                                wire:click="triggerManualCrawl"
                                wire:loading.attr="disabled"
                                class="w-full bg-zinc-950 hover:bg-zinc-900 text-white font-semibold text-xs rounded-md py-2 transition-all duration-150  flex items-center justify-center gap-1.5">
                            <span wire:loading.remove wire:target="triggerManualCrawl" class="flex items-center gap-1.5">
                                <i class="ph ph-lightning text-base"></i>
                                {{ __('Jalankan Scrape Manual') }}
                            </span>
                            <span wire:loading wire:target="triggerManualCrawl" class="flex items-center gap-1.5">
                                <span class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                {{ __('Memicu Scraper...') }}
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Bottom Config Block: Throttling Rules (Left) and Proxy Pool (Right) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">
                    <!-- Left: Throttling Rules Configuration (1/3) -->
                    <div class="lg:col-span-1 bg-white border border-[#e7e3e9]/80 rounded-lg p-4 flex flex-col justify-between ">
                        <div>
                            <div class="flex items-center gap-2 pb-3 mb-4 border-b border-zinc-100">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-[#e7e3e9] flex items-center justify-center text-zinc-650">
                                    <i class="ph-bold ph-sliders text-xs"></i>
                                </div>
                                <h2 class="text-xs font-sans font-medium text-zinc-500  ">{{ __('Throttling Rules') }}</h2>
                            </div>

                            <!-- Domain Selector Tabs -->
                            <div class="space-y-1 mb-5">
                                @foreach ($sources as $source)
                                    <button type="button"
                                            wire:click="editSource({{ $source['id'] }})"
                                            class="w-full text-left px-3 py-2.5 rounded-md text-xs flex items-center justify-between border transition-all duration-150 {{ $editingSourceId === $source['id'] ? 'bg-zinc-900 border-zinc-900 text-white font-medium ' : 'bg-zinc-50/50 border-[#e7e3e9]/80 text-zinc-600 hover:bg-zinc-100/50 hover:text-zinc-900' }}">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $source['is_active'] ? 'bg-emerald-500 ' : 'bg-zinc-300' }}"></span>
                                            <span class="tracking-tight">{{ $source['name'] }}</span>
                                        </div>
                                        <span class="text-[11px] font-sans opacity-80 ">{{ str_replace('.com', '', str_replace('.co.id', '', $source['target_domain'])) }}</span>
                                    </button>
                                @endforeach
                            </div>

                            @if ($editingSourceId)
                                <!-- Settings Input Form -->
                                <form wire:submit.prevent="saveSourceSettings" class="space-y-4">

                                    <!-- Toggle Switch -->
                                    <div class="flex items-center justify-between bg-zinc-50/50 border border-[#e7e3e9]/80 rounded-md p-3">
                                        <div>
                                            <label class="text-xs font-semibold text-zinc-900 tracking-tight">{{ __('Active Crawler Status') }}</label>
                                            <p class="text-[11px] text-zinc-500">{{ __('Run cron cycles for this target') }}</p>
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
                                            <label class="text-[11px] font-sans font-medium text-zinc-500  ">{{ __('Politeness Delay') }}</label>
                                            <span class="text-xs font-sans font-semibold text-zinc-955">{{ __(':value1ms', ['value1' => ($delay_between_requests_ms)]) }}</span>
                                        </div>
                                        <input type="range" min="0" max="10000" step="250"
                                               wire:model.live="delay_between_requests_ms"
                                               class="w-full accent-zinc-955 bg-zinc-150 h-1.5 rounded-lg cursor-pointer">
                                    </div>

                                    <!-- Concurrency Limit -->
                                    <div>
                                        <div class="flex justify-between items-baseline mb-1">
                                            <label class="text-[11px] font-sans font-medium text-zinc-500  ">{{ __('Concurrency Limit') }}</label>
                                            <span class="text-xs font-sans font-semibold text-zinc-955">{{ __(':value1 parallel', ['value1' => ($max_concurrency)]) }}</span>
                                        </div>
                                        <input type="range" min="1" max="15" step="1"
                                               wire:model.live="max_concurrency"
                                               class="w-full accent-zinc-955 bg-zinc-150 h-1.5 rounded-lg cursor-pointer">
                                    </div>

                                    <!-- Schedule Interval -->
                                    <div>
                                        <label class="text-[11px] font-sans font-medium text-zinc-500   block mb-1">{{ __('Crawl Frequency Interval') }}</label>
                                        <div class="relative">
                                            <input type="number"
                                                   wire:model="frequency_minutes"
                                                   class="w-full text-xs bg-zinc-50/50 border border-[#e7e3e9] rounded-md px-3 py-2 text-zinc-900 font-sans focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 focus:outline-hidden"
                                                   required>
                                            <span class="absolute right-3 top-2 text-[11px] font-sans text-zinc-500">{{ __('minutes') }}</span>
                                        </div>
                                    </div>

                                    <button type="submit"
                                            class="w-full bg-zinc-950 hover:bg-zinc-900 text-white font-medium text-xs rounded-md py-2 transition-all duration-150  flex items-center justify-center gap-1.5">
                                        <i class="ph ph-check"></i>
                                        {{ __('Save Config Settings') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Status Egress Scraper (2/3 width) -->
                    <div class="lg:col-span-2 bg-white border border-[#e7e3e9]/80 rounded-lg p-4 flex flex-col justify-between ">
                        <div>
                            <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded bg-zinc-50 border border-[#e7e3e9] flex items-center justify-center text-zinc-650">
                                        <i class="ph-bold ph-shield-check text-xs"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-xs font-sans font-medium text-zinc-500  ">{{ __('Status Egress Scraper') }}</h2>
                                        <p class="text-[11px] text-zinc-500">{{ __('Rotasi proxy eksternal dinonaktifkan') }}</p>
                                    </div>
                                </div>

                                <button type="button"
                                        wire:click="checkProxyHealth"
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1 bg-zinc-950 hover:bg-zinc-900 text-white font-semibold text-[11px] rounded px-2.5 py-1.5 transition-all duration-150  disabled:opacity-50">
                                    <span wire:loading.remove wire:target="checkProxyHealth" class="flex items-center gap-1">
                                        <i class="ph ph-heartbeat"></i> {{ __('Status Egress') }}
                                    </span>
                                    <span wire:loading wire:target="checkProxyHealth" class="flex items-center gap-1">
                                        <span class="w-2.5 h-2.5 border border-white border-t-transparent rounded-full animate-spin"></span>
                                        {{ __('Memverifikasi...') }}
                                    </span>
                                </button>
                            </div>

                            <!-- Proxy Cards Grid -->
                            @if (count($proxyStatusList) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($proxyStatusList as $index => $proxyItem)
                                        <div class="border rounded-md p-3 flex flex-col justify-between space-y-2  {{ $proxyItem['class'] }}">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[11px] font-sans font-medium   text-zinc-500">{{ __('PROXY #:value1', ['value1' => ($index + 1)]) }}</span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[7px] font-sans font-semibold border border-current">
                                                    {{ $proxyItem['status'] }}
                                                </span>
                                            </div>
                                            <div class="space-y-1 text-[11px] font-sans">
                                                <span class="text-zinc-800 truncate block">{{ parse_url($proxyItem['address'], PHP_URL_HOST) ?? $proxyItem['address'] }}</span>
                                                <span class="text-zinc-500 font-medium block">{{ __('IP: :value1', ['value1' => ($proxyItem['ip'])]) }}</span>
                                            </div>
                                            <div class="flex justify-between border-t border-[#e7e3e9]/50 pt-1.5 text-[11px] font-sans">
                                                <span>{{ __('Latency:') }}</span>
                                                <span class="font-medium text-zinc-850">{{ $proxyItem['latency'] > 0 ? $proxyItem['latency'] . __(' ms') : __('N/A') }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                @if ($isPingingProxies)
                                    <div class="flex items-center justify-center p-8 text-center border border-dashed rounded bg-zinc-50 border-[#e7e3e9]/80">
                                        <span class="w-5 h-5 border-2 border-zinc-950 border-t-transparent rounded-full animate-spin mr-2"></span>
                                        <p class="text-[11px] text-zinc-650">{{ __('Memeriksa status egress...') }}</p>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center p-8 text-center border border-dashed rounded bg-zinc-50 border-[#e7e3e9]/80 border-dashed">
                                        <p class="text-[11px] font-semibold text-zinc-500 mb-1">{{ __('Proxy Eksternal Dinonaktifkan') }}</p>
                                        <p class="text-[11px] text-zinc-500">{{ __('Scraper memakai egress tervalidasi untuk portal lowongan yang didukung.') }}</p>
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
                <div class="lg:col-span-1 bg-white border border-[#e7e3e9]/80 rounded-lg p-4  flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 pb-3 mb-4 border-b border-zinc-100">
                            <div class="w-6 h-6 rounded bg-zinc-50 border border-[#e7e3e9] flex items-center justify-center text-zinc-650">
                                <i class="ph-bold ph-crosshair text-xs"></i>
                            </div>
                            <h2 class="text-xs font-sans font-medium text-zinc-500  ">{{ __('Scraping Target Goal') }}</h2>
                        </div>

                        <form wire:submit.prevent="runTargetedScraping" class="space-y-4">
                            <!-- Sector Select -->
                            <div>
                                <label class="text-[11px] font-sans font-medium text-zinc-500   block mb-1">{{ __('Pilih Sektor Ekonomi') }}</label>
                                <select wire:model.live="targetSector"
                                        class="w-full text-xs bg-zinc-50/50 border border-[#e7e3e9] rounded-md px-3 py-2 text-zinc-900 focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 focus:outline-hidden"
                                        required>
                                    <option value="">{{ __('-- Pilih Sektor --') }}</option>
                                    @foreach (\App\Helpers\CategoryHelper::getSektors() as $sektor)
                                        <option value="{{ $sektor }}">{{ $sektor }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Major Select -->
                            <div>
                                <label class="text-[11px] font-sans font-medium text-zinc-500   block mb-1">{{ __('Pilih Rumpun Jurusan') }}</label>
                                <select wire:model="targetMajor"
                                        class="w-full text-xs bg-zinc-50/50 border border-[#e7e3e9] rounded-md px-3 py-2 text-zinc-900 focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 focus:outline-hidden"
                                        {{ empty($targetSector) ? 'disabled' : '' }}
                                        required>
                                    <option value="">{{ __('-- Pilih Jurusan --') }}</option>
                                    @foreach ($this->targetMajorsList as $majorOption)
                                        <option value="{{ $majorOption }}">{{ $majorOption }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quantity Limit -->
                            <div>
                                <label class="text-[11px] font-sans font-medium text-zinc-500   block mb-1">{{ __('Target Jumlah Loker Baru') }}</label>
                                <div class="relative">
                                    <input type="number"
                                           wire:model="targetJobsCount"
                                           min="5" max="300"
                                           class="w-full text-xs bg-zinc-50/50 border border-[#e7e3e9] rounded-md px-3 py-2 text-zinc-900 font-sans focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 focus:outline-hidden"
                                           required>
                                    <span class="absolute right-3 top-2 text-[11px] font-sans text-zinc-500">{{ __('loker baru') }}</span>
                                </div>
                                <span class="text-[11px] text-zinc-500 mt-1 block">{{ __('Scraper akan berhenti secara otomatis saat database bertambah sebanyak jumlah target di atas.') }}</span>
                            </div>

                            <button type="submit"
                                    class="w-full bg-zinc-950 hover:bg-zinc-900 text-white font-medium text-xs rounded-md py-2 transition-all duration-150  flex items-center justify-center gap-1.5">
                                <i class="ph ph-rocket-launch"></i>
                                {{ __('Jalankan Misi Targeted Ingestion') }}
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 pt-4 border-t border-zinc-150 text-[11px] text-zinc-500 space-y-1.5 font-sans leading-tight">
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>{{ __('Misi berjalan otomatis di background.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Live Progress Dashboard -->
                <div class="lg:col-span-2 bg-white border border-[#e7e3e9]/80 rounded-lg p-4  flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-[#e7e3e9] flex items-center justify-center text-zinc-650">
                                    <i class="ph-bold ph-activity text-xs"></i>
                                </div>
                                <h2 class="text-xs font-sans font-medium text-zinc-500  ">{{ __('Misi Aktif & Status Real-Time') }}</h2>
                            </div>
                        </div>

                        @if ($targetedProgress)
                            <div class="space-y-6">
                                <!-- Progress Info Card -->
                                <div class="bg-zinc-50 border border-[#e7e3e9] rounded-lg p-5 space-y-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-sans font-medium {{ $targetedProgress['status'] === 'RUNNING' ? 'bg-amber-50 text-amber-700 border border-amber-200 ' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                                {{ __('STATUS: :value1', ['value1' => ($targetedProgress['status'])]) }}
                                            </span>
                                            <h3 class="text-sm font-semibold text-zinc-900 mt-1.5">{{ $targetedProgress['major'] }}</h3>
                                            <p class="text-[11px] text-zinc-500">{{ $targetedProgress['sector'] }}</p>
                                        </div>

                                        @if ($targetedProgress['status'] === 'RUNNING')
                                            <button type="button"
                                                    wire:click="cancelTargetedScraping"
                                                    class="text-xs text-red-600 hover:text-red-800 font-medium px-2.5 py-1.5 border border-red-200 rounded-md hover:bg-red-50 transition-colors">
                                                {{ __('Batalkan Misi') }}
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Progress Bar -->
                                    <div>
                                        <div class="flex justify-between text-xs font-semibold mb-1">
                                            <span class="text-zinc-600">{{ __('Perkembangan Ingesti') }}</span>
                                            <span class="text-zinc-900 font-sans">{{ __(':value1 / :value2 Loker Baru (:value3%)', ['value1' => ($targetedProgress['current']), 'value2' => ($targetedProgress['target']), 'value3' => (round(($targetedProgress['current'] / $targetedProgress['target']) * 100))]) }}</span>
                                        </div>
                                        <div class="w-full bg-zinc-200 h-2 rounded-full overflow-hidden">
                                            <div class="bg-zinc-950 h-full rounded-full transition-all duration-300" style="width: {{ min(100, ($targetedProgress['current'] / $targetedProgress['target']) * 100) }}%"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 text-[11px] font-sans text-zinc-500 border-t border-zinc-150 pt-3">
                                        <div>{{ __('Mulai: :value1', ['value1' => ($targetedProgress['started_at'])]) }}</div>
                                        <div>{{ __('Selesai: :value1', ['value1' => ($targetedProgress['completed_at'] ?? '-')]) }}</div>
                                    </div>
                                </div>

                                <div class="bg-zinc-50 border border-zinc-150 rounded-lg p-5">
                                    <h4 class="text-[11px] font-sans font-medium text-zinc-500   mb-2.5">{{ __('Panduan Prosedur Targeted Ingestion') }}</h4>
                                    <ul class="text-[11px] text-zinc-500 space-y-1.5 leading-relaxed list-disc list-inside">
                                        <li>{{ __('Background worker akan secara khusus memprioritaskan antrean keyword relasi rumpun jurusan pilihan.') }}</li>
                                        <li>{{ __('Loker yang di-update (sudah terdaftar di DB sebelumnya) tidak dihitung dalam pertambahan kuota agar data yang masuk murni 100% baru.') }}</li>
                                        <li>{{ __('Anda dapat memantau detil lowongan baru yang masuk secara live pada box') }} <strong>{{ __('Live Log Streamer Console') }}</strong> {{ __('di halaman depan.') }}</li>
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="bg-zinc-50 border border-zinc-150 rounded-lg p-12 text-center flex flex-col items-center justify-center border-dashed h-full min-h-[300px]">
                                <i class="ph ph-crosshair text-zinc-300 text-4xl mb-3"></i>
                                <p class="text-xs font-semibold text-zinc-600 mb-1">{{ __('Belum Ada Misi Targeted Ingestion') }}</p>
                                <p class="text-[11px] text-zinc-500 max-w-sm">{{ __('Tentukan Sektor Ekonomi, Rumpun Jurusan, dan kuota target loker baru pada form kiri, lalu klik jalankan untuk memicu background crawling khusus.') }}</p>
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
                <div class="lg:col-span-1 bg-white border border-[#e7e3e9]/80 rounded-lg p-4  flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 pb-3 mb-4 border-b border-zinc-100">
                            <div class="w-6 h-6 rounded bg-zinc-50 border border-[#e7e3e9] flex items-center justify-center text-zinc-650">
                                <i class="ph-bold ph-terminal-window text-xs"></i>
                            </div>
                            <h2 class="text-xs font-sans font-medium text-zinc-500  ">{{ __('DOM Selectors Configuration') }}</h2>
                        </div>

                        <form wire:submit.prevent="runVisualSandboxTest" class="space-y-4">
                            <!-- Target URL -->
                            <div>
                                <label class="text-[11px] font-sans font-medium text-zinc-500   block mb-1">{{ __('Target URL Lowongan Detail') }}</label>
                                <input type="url"
                                       wire:model="sandboxUrl"
                                       placeholder="https://example.com/jobs/view/123"
                                       class="w-full text-xs bg-zinc-50/50 border border-[#e7e3e9] rounded-md px-3 py-2 text-zinc-900 focus:bg-white focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 focus:outline-hidden"
                                       required>
                                @error('sandboxUrl')
                                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Title Selector -->
                            <div>
                                <label class="text-[11px] font-sans font-medium text-zinc-500   block mb-1">{{ __('Selector CSS Judul Lowongan') }}</label>
                                <input type="text"
                                       wire:model="sandboxTitleSelector"
                                       placeholder="{{ __('h1.job-title atau .title-class') }}"
                                       class="w-full text-xs bg-zinc-50/50 border border-[#e7e3e9] rounded-md px-3 py-2 text-zinc-900 font-sans focus:bg-white focus:ring-1 focus:ring-zinc-955 focus:border-zinc-955 focus:outline-hidden"
                                       required>
                                @error('sandboxTitleSelector')
                                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Company Selector -->
                            <div>
                                <label class="text-[11px] font-sans font-medium text-zinc-500   block mb-1">{{ __('Selector CSS Perusahaan (Opsional)') }}</label>
                                <input type="text"
                                       wire:model="sandboxCompanySelector"
                                       placeholder="{{ __('span.company atau .company-name') }}"
                                       class="w-full text-xs bg-zinc-50/50 border border-[#e7e3e9] rounded-md px-3 py-2 text-zinc-900 font-sans focus:bg-white focus:ring-1 focus:ring-zinc-955 focus:border-zinc-955 focus:outline-hidden">
                            </div>

                            <!-- Body Selector -->
                            <div>
                                <label class="text-[11px] font-sans font-medium text-zinc-500   block mb-1">{{ __('Selector CSS Deskripsi Lowongan (Opsional)') }}</label>
                                <input type="text"
                                       wire:model="sandboxBodySelector"
                                       placeholder="{{ __('div.job-description atau #details') }}"
                                       class="w-full text-xs bg-zinc-50/50 border border-[#e7e3e9] rounded-md px-3 py-2 text-zinc-900 font-sans focus:bg-white focus:ring-1 focus:ring-zinc-955 focus:border-zinc-955 focus:outline-hidden">
                            </div>

                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    class="w-full bg-zinc-950 hover:bg-zinc-900 disabled:bg-zinc-400 text-white font-medium text-xs rounded-md py-2 transition-all duration-150  flex items-center justify-center gap-1.5">
                                <span wire:loading.remove wire:target="runVisualSandboxTest">{{ __('Uji Coba Selektor CSS') }}</span>
                                <span wire:loading wire:target="runVisualSandboxTest" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 pt-4 border-t border-zinc-150 text-[11px] text-zinc-500 space-y-1.5 font-sans leading-tight">
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-zinc-300"></span>
                            <span>{{ __('Mendukung rendering Puppeteer jika diaktifkan.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Test Preview Cards & Logs Output -->
                <div class="lg:col-span-2 bg-white border border-[#e7e3e9]/80 rounded-lg p-4  flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-zinc-50 border border-[#e7e3e9] flex items-center justify-center text-zinc-650">
                                    <i class="ph-bold ph-eye text-xs"></i>
                                </div>
                                <h2 class="text-xs font-sans font-medium text-zinc-500  ">{{ __('Hasil Ekstraksi & Pratinjau DOM') }}</h2>
                            </div>
                        </div>

                        <!-- Sandbox Console Logs -->
                        <div class="mb-5">
                            <p class="text-[11px] font-sans font-medium text-zinc-500   mb-2">{{ __('Logs Jalur Sandbox') }}</p>
                            @if (count($sandboxLogs) > 0)
                                <div class="bg-[#121214] text-[#a1a1aa] border border-zinc-800 p-4 rounded-lg font-sans text-[11px] leading-relaxed shadow-inner max-h-[150px] overflow-y-auto space-y-1.5 custom-scrollbar">
                                    @foreach ($sandboxLogs as $log)
                                        <div class="whitespace-pre-wrap tracking-tight">{{ $log }}</div>
                                    @endforeach

                                    @if ($sandboxIsTesting)
                                        <div class="flex items-center gap-1.5 text-zinc-550 mt-1 ">
                                            <span class="w-1.5 h-1.5 bg-zinc-500 rounded-full "></span>
                                            <span>{{ __('Mengekstrak DOM dengan selectors...') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="bg-zinc-50 border border-zinc-150 rounded-lg p-8 text-center flex flex-col items-center justify-center border-dashed">
                                    <i class="ph ph-code text-zinc-500 text-2xl mb-2"></i>
                                    <p class="text-xs font-semibold text-zinc-650 mb-0.5">{{ __('Logs pengujian kosong') }}</p>
                                    <p class="text-[11px] text-zinc-500 max-w-sm">{{ __('Masukkan URL dan selector CSS kustom Anda di sisi kiri, lalu klik uji coba untuk melihat hasil parsing secara detail.') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Extracted Payload Preview -->
                        @if ($sandboxExtractedPayload)
                            <div class="border border-[#e7e3e9] rounded-lg p-4 bg-zinc-50/50 space-y-3.5 ">
                                <div class="flex items-center justify-between border-b border-[#e7e3e9] pb-2.5">
                                    <span class="text-[11px] font-sans font-medium text-zinc-500  ">{{ __('Payload Terurai (Extracted)') }}</span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-sans font-semibold {{ $sandboxTestResultStatus === 'SUCCESS' ? 'bg-emerald-50 text-emerald-700 border border-emerald-205' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        [{{ $sandboxTestResultStatus }}]
                                    </span>
                                </div>

                                <div class="space-y-3 text-xs tracking-tight">
                                    <div>
                                        <span class="text-[11px] text-zinc-500 font-sans block   mb-0.5">{{ __('Judul Lowongan') }}</span>
                                        <span class="font-semibold text-zinc-900 text-sm">{{ $sandboxExtractedPayload['title'] }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[11px] text-zinc-500 font-sans block   mb-0.5">{{ __('Nama Perusahaan') }}</span>
                                        <span class="font-semibold text-zinc-900">{{ $sandboxExtractedPayload['company'] }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[11px] text-zinc-500 font-sans block   mb-0.5">{{ __('Potongan Deskripsi Loker') }}</span>
                                        <div class="bg-white border border-[#e7e3e9] rounded p-2.5 font-sans text-[11px] text-zinc-650 max-h-[100px] overflow-y-auto leading-relaxed">
                                            {{ $sandboxExtractedPayload['description'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-4 border-t border-zinc-100 text-[11px] font-sans text-zinc-500">
                        {{ __('Visual selector builder bermanfaat untuk memotong bug perubahan struktur HTML pada platform loker eksternal.') }}
                    </div>
                </div>
            </div>
        @endif

        @if ($activeTab === 'reports')
            <!-- TAB 5: REPORTED JOB LISTINGS MODERATION -->
            <div class="space-y-5 animate-fade-in text-zinc-900">
                <!-- Status Notifications / Session Alert -->
                @if (session()->has('report_action_success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg p-3 text-xs font-semibold flex items-center gap-2 select-none ">
                        <i class="ph-bold ph-check-circle text-sm text-emerald-600"></i>
                        <span>{{ session('report_action_success') }}</span>
                    </div>
                @endif

                <div class="bg-white border border-[#e7e3e9]/80 rounded-lg p-5 ">
                    <div class="flex items-center justify-between pb-3.5 border-b border-zinc-150/60 mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-650 ">
                                <i class="ph-bold ph-warning-octagon text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-medium text-zinc-800 tracking-tight">{{ __('Moderasi Laporan Lowongan Ditutup') }}</h3>
                                <p class="text-zinc-500 text-[11px] font-medium mt-0.5">{{ __('Daftar lowongan kerja yang dilaporkan sudah ditutup oleh pengguna di halaman pencarian.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if(count($reportedJobs) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-[11px] text-zinc-650 border-collapse">
                                <thead>
                                    <tr class="bg-zinc-50 border-b border-[#e7e3e9] text-left font-sans font-medium text-zinc-500   text-[11px]">
                                        <th class="py-2.5 px-3">{{ __('Detail Lowongan') }}</th>
                                        <th class="py-2.5 px-3">{{ __('Platform') }}</th>
                                        <th class="py-2.5 px-3 text-center">{{ __('Jumlah Laporan') }}</th>
                                        <th class="py-2.5 px-3">{{ __('Status Saat Ini') }}</th>
                                        <th class="py-2.5 px-3 text-right">{{ __('Aksi Audit') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-150/60">
                                    @foreach($reportedJobs as $job)
                                        <tr class="hover:bg-zinc-50/30 transition-colors duration-150">
                                            <td class="py-3 px-3">
                                                <div class="font-medium text-zinc-800">{{ $job->title }}</div>
                                                <div class="text-[11px] text-zinc-500 font-medium mt-0.5">{{ __(':value1 • :value2', ['value1' => ($job->company_name), 'value2' => ($job->location ?: __('Indonesia'))]) }}</div>
                                            </td>
                                            <td class="py-3 px-3 font-sans font-medium text-[11px]">
                                                @php
                                                    $portalName = str_contains($job->scraperSource->target_domain, 'linkedin') ? 'LinkedIn' : (str_contains($job->scraperSource->target_domain, 'jobstreet') ? 'JobStreet' : 'Kalibrr');
                                                    $badgeStyle = 'bg-blue-50 text-blue-700 border border-blue-200';
                                                    if ($portalName === 'JobStreet') {
                                                        $badgeStyle = 'bg-red-50 text-red-700 border border-red-200';
                                                    } elseif ($portalName === 'Kalibrr') {
                                                        $badgeStyle = 'bg-emerald-50 text-emerald-700 border border-emerald-250';
                                                    }
                                                @endphp
                                                <span class="px-1.5 py-0.5 rounded text-[8.5px]  font-medium {{ $badgeStyle }} shrink-0">
                                                    {{ $portalName }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-3 text-center">
                                                <div class="inline-flex items-center gap-1 px-2 py-0.5 bg-rose-50 border border-rose-150 rounded text-rose-700 font-sans font-medium text-[11px]">
                                                    <i class="ph-bold ph-warning text-[11px]"></i>
                                                    {{ __(':value1x Dilaporkan', ['value1' => ($job->report_dead_count)]) }}
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 font-sans font-medium text-[11px] ">
                                                @if($job->status === 'closed')
                                                    <span class="text-zinc-500 bg-zinc-100 border border-[#e7e3e9] px-1.5 py-0.5 rounded">{{ __('Closed/Archived') }}</span>
                                                @elseif($job->status === 'reported_dead')
                                                    <span class="text-red-700 bg-red-50 border border-red-200 px-1.5 py-0.5 rounded">{{ __('Reported Dead') }}</span>
                                                @else
                                                    <span class="text-emerald-700 bg-emerald-50 border border-emerald-250 px-1.5 py-0.5 rounded">{{ __('Active') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-3 text-right">
                                                <div class="flex items-center justify-end gap-2.5">
                                                    <!-- Check original link -->
                                                    <a href="{{ $job->raw_url }}"
                                                       target="_blank"
                                                       class="text-[9.5px] font-medium text-zinc-500 hover:text-zinc-800 transition-colors   flex items-center gap-0.5">
                                                        <i class="ph ph-arrow-square-out text-xs"></i> {{ __('Cek Link') }}
                                                    </a>
                                                    <span class="text-zinc-200">|</span>
                                                    <!-- Restore / Re-activate -->
                                                    <button type="button"
                                                            wire:click="restoreReportedJob({{ $job->id }})"
                                                            class="text-[9.5px] font-medium text-emerald-600 hover:text-emerald-800 transition-colors   flex items-center gap-0.5">
                                                        <i class="ph ph-check text-xs"></i> {{ __('Pulihkan') }}
                                                    </button>
                                                    <span class="text-zinc-200">|</span>
                                                    <!-- Close permanently -->
                                                    <button type="button"
                                                            wire:click="closeReportedJob({{ $job->id }})"
                                                            class="text-[9.5px] font-medium text-rose-600 hover:text-rose-800 transition-colors   flex items-center gap-0.5">
                                                        <i class="ph ph-archive text-xs"></i> {{ __('Arsipkan') }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="border border-dashed border-[#e7e3e9] rounded-2xl p-12 text-center flex flex-col items-center justify-center bg-zinc-50/30 select-none">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500 mb-3 ">
                                <i class="ph-bold ph-shield-check text-base"></i>
                            </div>
                            <h3 class="text-xs font-medium text-zinc-800 tracking-tight">{{ __('Semua Bersih') }}</h3>
                            <p class="text-[11px] text-zinc-500 max-w-[280px] mt-1 leading-normal">{{ __('Tidak ada laporan aktif dari pengguna saat ini. Semua lowongan dalam status operasional normal.') }}</p>
                        </div>
                    @endif
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

                const getChartTheme = () => {
                    const isDark = document.documentElement.classList.contains('dark');
                    return {
                        textColor: isDark ? '#94a3b8' : '#71717a',
                        gridColor: isDark ? 'rgba(255, 255, 255, 0.08)' : '#f4f4f5'
                    };
                };

                const theme = getChartTheme();

                window.scraperChart = new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: @js(__('Sukses')),
                                data: successData,
                                backgroundColor: '#729c94', // emerald-500
                                borderRadius: 4,
                                maxBarThickness: 24,
                            },
                            {
                                label: @js(__('Gagal / Diblokir')),
                                data: failData,
                                backgroundColor: '#c58299', // rose-500
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
                                    color: theme.textColor
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                grid: { display: false },
                                ticks: {
                                    font: { size: 9, family: 'Plus Jakarta Sans', weight: 600 },
                                    color: theme.textColor
                                }
                            },
                            y: {
                                stacked: true,
                                grid: { color: theme.gridColor, drawTicks: false },
                                border: { dash: [4, 4] },
                                ticks: {
                                    font: { size: 9, family: 'Plus Jakarta Sans', weight: 600 },
                                    color: theme.textColor
                                }
                            }
                        }
                    }
                });

                window.addEventListener('theme-changed', function () {
                    if (window.scraperChart) {
                        const newTheme = getChartTheme();
                        window.scraperChart.options.plugins.legend.labels.color = newTheme.textColor;
                        window.scraperChart.options.scales.x.ticks.color = newTheme.textColor;
                        window.scraperChart.options.scales.y.ticks.color = newTheme.textColor;
                        window.scraperChart.options.scales.y.grid.color = newTheme.gridColor;
                        window.scraperChart.update();
                    }
                });
            }
        });
    </script>
</div>
