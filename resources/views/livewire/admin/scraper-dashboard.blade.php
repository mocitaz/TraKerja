<div>
    <!-- Header -->
    <div class="mb-5 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-mono text-zinc-400 uppercase tracking-wider mb-1">
                <span>Admin</span>
                <span class="text-zinc-300">/</span>
                <span>System</span>
                <span class="text-zinc-300">/</span>
                <span class="text-zinc-600 font-semibold">Scraper Engine</span>
            </div>
            <h1 class="text-base font-bold text-zinc-950 tracking-tight">Scraper Engine & Validation Sandbox</h1>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-1"></span>
                Engine Ready
            </span>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="ph-bold ph-check-circle text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Ingestion stats Bento Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-3xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider">Total Ingested Jobs</span>
                <i class="ph ph-database text-zinc-400 text-base"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-xl font-bold text-zinc-950 tracking-tight">{{ number_format($stats['total_ingested']) }}</span>
                <span class="text-[9px] font-medium text-emerald-600">+12% this week</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-3xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider">Scraper Success Rate</span>
                <i class="ph ph-shield-check text-emerald-500 text-base"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-xl font-bold text-zinc-950 tracking-tight">{{ $stats['success_rate'] }}%</span>
                <span class="text-[9px] font-medium text-zinc-400">Target 95%+</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-3xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider">Active Platforms</span>
                <i class="ph ph-globe text-zinc-400 text-base"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-xl font-bold text-zinc-950 tracking-tight">{{ $stats['active_sources'] }} / 3</span>
                <span class="text-[9px] font-medium text-zinc-400">LinkedIn, JobStreet, Kalibrr</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-3xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider">Estimated Proxy Cost</span>
                <i class="ph ph-currency-dollar text-zinc-400 text-base"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-xl font-bold text-zinc-950 tracking-tight">${{ number_format($stats['estimated_cost'], 4) }}</span>
                <span class="text-[9px] font-medium text-zinc-400">Residential plan</span>
            </div>
        </div>
    </div>

    <!-- Main Workspace -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        <!-- Configuration & Throttling (Left Column - 1/3) -->
        <div class="lg:col-span-1 bg-white border border-zinc-200 rounded-xl p-5 shadow-3xs flex flex-col justify-between h-full">
            <div>
                <div class="flex items-center justify-between border-b border-zinc-100 pb-3 mb-4">
                    <h2 class="text-xs font-bold text-zinc-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="ph-bold ph-sliders text-zinc-700 text-sm"></i>
                        Throttling & politeness
                    </h2>
                </div>

                <!-- Source Selector Tabs -->
                <div class="flex flex-col gap-1.5 mb-5">
                    @foreach ($sources as $source)
                        <button type="button" 
                                wire:click="editSource({{ $source['id'] }})"
                                class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between border transition-all duration-200 {{ $editingSourceId === $source['id'] ? 'bg-zinc-950 border-zinc-950 text-white font-semibold shadow-xs' : 'bg-zinc-50 border-zinc-200 text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900' }}">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $source['is_active'] ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                <span>{{ $source['name'] }}</span>
                            </div>
                            <span class="text-[10px] font-mono opacity-80">{{ $source['target_domain'] }}</span>
                        </button>
                    @endforeach
                </div>

                @if ($editingSourceId)
                    <!-- Editing Form Settings -->
                    <form wire:submit.prevent="saveSourceSettings" class="space-y-4">
                        <!-- Active Status Switch -->
                        <div class="flex items-center justify-between bg-zinc-50 rounded-lg p-3 border border-zinc-200">
                            <div>
                                <label class="text-xs font-bold text-zinc-900">Enable Crawler</label>
                                <p class="text-[10px] text-zinc-500">Run scheduled crawls for this source</p>
                            </div>
                            <button type="button" 
                                    wire:click="$set('is_active', {{ $is_active ? 'false' : 'true' }})"
                                    class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden {{ $is_active ? 'bg-zinc-900' : 'bg-zinc-200' }}">
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out {{ $is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                        </div>

                        <!-- Delay requests -->
                        <div>
                            <div class="flex justify-between items-baseline mb-1">
                                <label class="text-[11px] font-bold text-zinc-800 uppercase tracking-wider">Politeness Delay (ms)</label>
                                <span class="text-xs font-mono text-zinc-500">{{ $delay_between_requests_ms }}ms</span>
                            </div>
                            <input type="range" min="0" max="10000" step="250" 
                                   wire:model.live="delay_between_requests_ms"
                                   class="w-full accent-zinc-900 bg-zinc-200 h-1 rounded-lg cursor-pointer">
                            <p class="text-[10px] text-zinc-400 mt-1">Wait time between consecutive listing visits</p>
                        </div>

                        <!-- Max Concurrency -->
                        <div>
                            <div class="flex justify-between items-baseline mb-1">
                                <label class="text-[11px] font-bold text-zinc-800 uppercase tracking-wider">Max Concurrency</label>
                                <span class="text-xs font-mono text-zinc-500">{{ $max_concurrency }} workers</span>
                            </div>
                            <input type="range" min="1" max="20" step="1" 
                                   wire:model.live="max_concurrency"
                                   class="w-full accent-zinc-900 bg-zinc-200 h-1 rounded-lg cursor-pointer">
                            <p class="text-[10px] text-zinc-400 mt-1">Maximum parallel chromium instances per worker node</p>
                        </div>

                        <!-- Frequency -->
                        <div>
                            <div class="flex justify-between items-baseline mb-1">
                                <label class="text-[11px] font-bold text-zinc-800 uppercase tracking-wider">Interval (Minutes)</label>
                                <span class="text-xs font-mono text-zinc-500">{{ $frequency_minutes }} mins</span>
                            </div>
                            <input type="number" 
                                   wire:model="frequency_minutes"
                                   class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-2.5 py-1.5 text-zinc-900 focus:outline-hidden focus:border-zinc-950 font-mono">
                            <p class="text-[10px] text-zinc-400 mt-1">Recurrence cooldown for finding seed updates</p>
                        </div>

                        <!-- Save Trigger -->
                        <button type="submit" 
                                class="w-full bg-zinc-950 hover:bg-zinc-900 text-white font-medium text-xs rounded py-2 transition-colors duration-150">
                            Apply Crawler Rules
                        </button>
                    </form>
                @endif
            </div>

            <!-- Note / Footer -->
            <div class="mt-6 border-t border-zinc-100 pt-4 text-[10px] text-zinc-400 space-y-1">
                <p>💡 Throttling policies write to Redis dynamically.</p>
                <p>🛡️ Active throttling triggered if failures exceed 15%.</p>
            </div>
        </div>

        <!-- Sandbox & Real-time execution logs (Right Column - 2/3) -->
        <div class="lg:col-span-2 bg-white border border-zinc-200 rounded-xl p-5 shadow-3xs flex flex-col justify-between h-full">
            <div>
                <!-- Header Sandbox -->
                <div class="flex items-center justify-between border-b border-zinc-100 pb-3 mb-4">
                    <h2 class="text-xs font-bold text-zinc-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="ph-bold ph-terminal-window text-zinc-700 text-sm"></i>
                        Test Ping Bot Sandbox
                    </h2>
                </div>

                <!-- Input section -->
                <form wire:submit.prevent="runTestSandbox" class="space-y-4 mb-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div class="md:col-span-3">
                            <input type="url" 
                                   wire:model="testUrl"
                                   placeholder="Paste raw Job URL here (e.g., https://id.linkedin.com/jobs/view/...)" 
                                   class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-3 py-2 text-zinc-900 focus:outline-hidden focus:border-zinc-950"
                                   required>
                        </div>
                        <div class="md:col-span-1">
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="w-full bg-zinc-900 hover:bg-zinc-950 disabled:bg-zinc-400 text-white font-medium text-xs rounded px-4 py-2 transition-colors duration-150 flex items-center justify-center gap-1.5">
                                <span wire:loading.remove>Run Test Bot</span>
                                <span wire:loading class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            </button>
                        </div>
                    </div>
                    @error('testUrl')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </form>

                <!-- Output Sandbox logs -->
                @if (count($logs) > 0)
                    <div class="mb-5">
                        <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-2">Terminal Logs</span>
                        <div class="bg-zinc-950 text-zinc-100 p-4 rounded-lg font-mono text-[10px] leading-relaxed shadow-inner max-h-[180px] overflow-y-auto space-y-1">
                            @foreach ($logs as $log)
                                <div class="whitespace-pre-wrap">{{ $log }}</div>
                            @endforeach
                            
                            @if ($isTesting)
                                <div class="flex items-center gap-1.5 text-zinc-400 mt-1">
                                    <span class="w-1.5 h-1.5 bg-zinc-400 rounded-full animate-ping"></span>
                                    <span>Executing headless evaluation...</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Empty state logs -->
                    <div class="bg-zinc-50 border border-zinc-200 rounded-lg p-10 text-center mb-5">
                        <i class="ph ph-terminal text-zinc-400 text-3xl mb-2 block mx-auto"></i>
                        <span class="text-xs font-medium text-zinc-500">Paste listing URL above to inspect proxy routes, response payloads, and active state indicators in real-time.</span>
                    </div>
                @endif

                <!-- Extracted Summary Payload Deck -->
                @if ($extractedPayload)
                    <div class="border border-zinc-200 rounded-xl p-4 bg-zinc-50/50 shadow-3xs space-y-3">
                        <div class="flex items-center justify-between border-b border-zinc-200/80 pb-2 mb-1">
                            <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Payload Summary</span>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-mono font-bold {{ $testResultStatus === 'SUCCESS' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                    [{{ $testResultStatus }}]
                                </span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-mono font-bold {{ $testJobStatus === 'ACTIVE' ? 'bg-emerald-500 text-white' : 'bg-zinc-700 text-white' }}">
                                    [JOB STATUS: {{ $testJobStatus }}]
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-[10px] text-zinc-400 font-medium block">Job Title</span>
                                <span class="font-semibold text-zinc-900">{{ $extractedPayload['title'] }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-zinc-400 font-medium block">Source Configuration</span>
                                <span class="font-semibold text-zinc-900">{{ $extractedPayload['company'] }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-zinc-400 font-medium block">Routed Proxy IP</span>
                                <span class="font-mono text-zinc-800 font-semibold">{{ $testProxyIp }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-zinc-400 font-medium block">Closing Indicator Detected</span>
                                <span class="font-semibold text-zinc-900">
                                    {{ $extractedPayload['closing_indicator_found'] ? 'YES' : 'NO' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Footer / System status info -->
            <div class="mt-6 border-t border-zinc-100 pt-4 flex flex-col sm:flex-row items-center justify-between text-[10px] text-zinc-400 gap-2">
                <span>Docker Container Status: <strong>ONLINE (node-test-01)</strong></span>
                <span>Active Residential Session IP: <strong>{{ $testProxyIp ?: 'None' }}</strong></span>
            </div>
        </div>
    </div>
</div>
