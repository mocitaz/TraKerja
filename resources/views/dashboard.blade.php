<x-app-layout>
    <x-slot name="header">
        <!-- Ignored layout slot, header is handled inline inside template container for premium consistency -->
    </x-slot>

    <div class="bg-[#fafafa] min-h-screen pb-16">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph ph-circles-four text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">Dashboard Overview</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 /* [BRAND_PRIMARY] */ text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60">Live</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Welcome back, <span class="font-bold text-zinc-700">{{ explode(' ', Auth::user()->name)[0] }}</span>! Here is your career momentum and tracking progress.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 shrink-0 self-start md:self-center">
                    <div class="flex items-center gap-2 px-2.5 py-1.5 bg-white border border-zinc-200/60 rounded-md text-[11px] font-semibold text-zinc-650 shadow-2xs">
                        <i class="ph ph-calendar text-primary-500 /* [BRAND_PRIMARY] */"></i>
                        <span>{{ now()->timezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>
            </div>

            <style>
                .custom-scrollbar::-webkit-scrollbar {
                    width: 4px;
                }
                .custom-scrollbar::-webkit-scrollbar-track {
                    background: transparent;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #e4e4e7;
                    border-radius: 4px;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: #d4d4d8;
                }
            </style>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                <!-- On Process Card -->
                <div class="bg-white rounded-lg border border-zinc-200/60 p-3.5 flex items-center justify-between transition-all hover:border-blue-400 hover:shadow-[0_4px_12px_rgba(59,130,246,0.02)] group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-50/50 rounded-md flex items-center justify-center text-blue-600">
                            <i class="ph ph-spinner-gap text-base animate-spin-slow"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none">On Process</p>
                            <p class="text-[10px] text-zinc-500 font-semibold mt-0.5 leading-none">Active Apps</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-zinc-800 tracking-tight leading-none">{{ $onProcessCount }}</p>
                    </div>
                </div>

                <!-- Offering/Accepted Card -->
                <div class="bg-white rounded-lg border border-zinc-200/60 p-3.5 flex items-center justify-between transition-all hover:border-emerald-400 hover:shadow-[0_4px_12px_rgba(16,185,129,0.02)] group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-50/50 rounded-md flex items-center justify-center text-emerald-600">
                            <i class="ph ph-check-circle text-base"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Success</p>
                            <p class="text-[10px] text-zinc-500 font-semibold mt-0.5 leading-none">Offers & Recs</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-zinc-800 tracking-tight leading-none">{{ $offeringAcceptedCount }}</p>
                    </div>
                </div>

                <!-- Declined Card -->
                <div class="bg-white rounded-lg border border-zinc-200/60 p-3.5 flex items-center justify-between transition-all hover:border-rose-400 hover:shadow-[0_4px_12px_rgba(244,63,94,0.02)] group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-rose-50/50 rounded-md flex items-center justify-center text-rose-600">
                            <i class="ph ph-x-circle text-base"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Declined</p>
                            <p class="text-[10px] text-zinc-500 font-semibold mt-0.5 leading-none">Rejected</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-zinc-800 tracking-tight leading-none">{{ $declinedCount }}</p>
                    </div>
                </div>

                <!-- Total Interviews Card -->
                <div class="bg-white rounded-lg border border-zinc-200/60 p-3.5 flex items-center justify-between transition-all hover:border-orange-400 hover:shadow-[0_4px_12px_rgba(245,158,11,0.02)] group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-50/50 rounded-md flex items-center justify-center text-orange-600">
                            <i class="ph ph-calendar text-base"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Interviews</p>
                            <p class="text-[10px] text-zinc-500 font-semibold mt-0.5 leading-none">Total Scheduled</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-zinc-800 tracking-tight leading-none">{{ $totalInterviewsCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Layout Main Area -->
            <!-- Row 1: Recent Applications & Weekly Targets (Equal Height Alignment) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5 items-stretch">
                <!-- Left: Recent Applications (col-span 2) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-4 h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-xs font-bold text-zinc-850 tracking-tight uppercase tracking-wider">Recent Applications</h3>
                                    <p class="text-[10px] text-zinc-400 font-medium">Your latest job submissions</p>
                                </div>
                                <a href="{{ route('tracker') }}" class="flex items-center gap-1.5 px-2.5 py-1 bg-white border border-zinc-200 hover:bg-zinc-50 rounded-md text-[10px] font-semibold text-zinc-600 transition-colors">
                                    <span>View Tracker</span>
                                    <i class="ph ph-arrow-right text-[10px]"></i>
                                </a>
                            </div>

                            @if($recentApplications->isEmpty())
                                <div class="flex flex-col items-center justify-center py-8 text-center bg-zinc-50/30 rounded-lg border border-dashed border-zinc-200/80">
                                    <div class="w-10 h-10 bg-zinc-100 rounded-md flex items-center justify-center text-zinc-400 mb-2.5">
                                        <i class="ph ph-briefcase text-lg"></i>
                                    </div>
                                    <h4 class="text-xs font-bold text-zinc-700">No applications yet</h4>
                                    <p class="text-[10px] text-zinc-400 mt-0.5 max-w-[220px]">Add your first job opportunity to start tracking your career progress!</p>
                                    <button onclick="openJobModal()" class="mt-3 flex items-center gap-1.5 px-2.5 py-1 bg-zinc-900 hover:bg-zinc-800 text-white text-[10px] font-bold rounded-md transition-all active:scale-95">
                                        <i class="ph ph-plus text-xs"></i>
                                        <span>Add Application</span>
                                    </button>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-zinc-150/40">
                                        <thead>
                                            <tr class="text-left text-[9px] font-bold text-zinc-400 uppercase tracking-wider bg-zinc-55/10">
                                                <th class="px-3 py-2">Company</th>
                                                <th class="px-3 py-2">Position</th>
                                                <th class="px-3 py-2 hidden sm:table-cell">Applied</th>
                                                <th class="px-3 py-2 text-center">Stage</th>
                                                <th class="px-3 py-2 text-center">Status</th>
                                                <th class="px-3 py-2 text-right pr-4">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-zinc-150/40 text-zinc-700 bg-white">
                                            @foreach($recentApplications as $job)
                                                <tr class="transition-colors cursor-pointer group hover:bg-zinc-50/45" onclick="window.location='{{ route('jobs.show', $job) }}'">
                                                    {{-- Company --}}
                                                    <td class="px-3 py-2.5 whitespace-nowrap">
                                                        <span class="text-xs font-bold text-zinc-800 group-hover:text-primary-600 transition-colors">{{ $job->company_name }}</span>
                                                    </td>
                                                    {{-- Position --}}
                                                    <td class="px-3 py-2.5 whitespace-nowrap">
                                                        <span class="text-xs font-medium text-zinc-650">{{ $job->position }}</span>
                                                    </td>
                                                    {{-- Applied --}}
                                                    <td class="px-3 py-2.5 whitespace-nowrap text-xs text-zinc-450 hidden sm:table-cell">
                                                        {{ $job->application_date ? $job->application_date->translatedFormat('d M Y') : '-' }}
                                                    </td>
                                                    {{-- Stage --}}
                                                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                        @php
                                                            $stageColors = [
                                                                'Applied' => ['bg' => '#3b82f612', 'text' => '#2563eb', 'border' => '#3b82f620'],
                                                                'HR - Interview' => ['bg' => '#f9731612', 'text' => '#ea580c', 'border' => '#f9731620'],
                                                                'User - Interview' => ['bg' => '#eab30812', 'text' => '#ca8a04', 'border' => '#eab30820'],
                                                                'Offering' => ['bg' => '#10b98112', 'text' => '#059669', 'border' => '#10b98120'],
                                                                'Rejected' => ['bg' => '#ef444412', 'text' => '#dc2626', 'border' => '#ef444420'],
                                                            ];
                                                            $stg = $stageColors[$job->recruitment_stage] ?? ['bg' => '#f4f4f5', 'text' => '#71717a', 'border' => '#e4e4e7'];
                                                        @endphp
                                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase" style="background-color: {{ $stg['bg'] }}; color: {{ $stg['text'] }}; border: 1px solid {{ $stg['border'] }};">
                                                            {{ $job->recruitment_stage }}
                                                        </span>
                                                    </td>
                                                    {{-- Status --}}
                                                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                        @php
                                                            $statusColors = [
                                                                'On Process' => ['bg' => '#3b82f612', 'text' => '#2563eb', 'border' => '#3b82f620'],
                                                                'Accepted' => ['bg' => '#10b98112', 'text' => '#059669', 'border' => '#10b98120'],
                                                                'Declined' => ['bg' => '#ef444412', 'text' => '#dc2626', 'border' => '#ef444420'],
                                                            ];
                                                            $stat = $statusColors[$job->application_status] ?? ['bg' => '#f4f4f5', 'text' => '#71717a', 'border' => '#e4e4e7'];
                                                        @endphp
                                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase" style="background-color: {{ $stat['bg'] }}; color: {{ $stat['text'] }}; border: 1px solid {{ $stat['border'] }};">
                                                            {{ $job->application_status }}
                                                        </span>
                                                    </td>
                                                    {{-- Action --}}
                                                    <td class="px-3 py-2.5 text-right pr-4 whitespace-nowrap">
                                                        <i class="ph ph-caret-right text-xs text-zinc-300 group-hover:text-zinc-500 transition-colors"></i>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Weekly Goal (col-span 1) -->
                <div class="col-span-1">
                    <livewire:goals-cadence-manager />
                </div>
            </div>

            <!-- Row 2: Heatmap, Upcoming Interviews, and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5 items-stretch">
                <!-- Left Column: Heatmap & Wawancara (col-span 2) -->
                <div class="lg:col-span-2 space-y-5">
                    <!-- Job Search Momentum Calendar Heatmap (GitHub Style) -->
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-5 shadow-2xs font-sans">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 select-none">
                            <div>
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight uppercase tracking-wider">{{ $totalHeatmapApps }} applications submitted in the last year</h3>
                                <p class="text-[10px] text-zinc-400 font-medium mt-0.5">Visualizing your daily application consistency over the last 12 months</p>
                            </div>
                            <div class="text-[9px] text-zinc-450 font-mono bg-zinc-50 border border-[#e4e4e7] px-2 py-0.5 rounded-md shrink-0">
                                Avg: <strong class="text-zinc-800">{{ number_format($totalHeatmapApps / 365, 2) }}</strong> / day
                            </div>
                        </div>

                        {{-- Heatmap Grid Container with Horizontal Scroll --}}
                        <div class="overflow-x-auto select-none custom-scrollbar pb-2 pt-4">
                            <div class="flex" style="gap: 3px; min-width: 760px; position: relative;">
                                {{-- Days Labels on Left --}}
                                <div class="flex flex-col justify-between select-none shrink-0 pr-2 font-sans font-medium text-zinc-400" style="height: 88px; font-size: 9px; line-height: 1;">
                                    <span style="height: 10px; display: flex; align-items: center;">Mon</span>
                                    <span style="height: 10px; display: flex; align-items: center;">Wed</span>
                                    <span style="height: 10px; display: flex; align-items: center;">Fri</span>
                                </div>

                                {{-- Week Columns --}}
                                @php
                                    $lastMonth = '';
                                    $dayOrder = [1, 2, 3, 4, 5, 6, 0]; // Monday to Sunday
                                @endphp
                                @foreach($heatmapWeeks as $weekKey => $days)
                                    @php
                                        $firstDay = \Carbon\Carbon::parse($weekKey);
                                        $monthName = '';
                                        if ($firstDay->format('M') !== $lastMonth) {
                                            $monthName = $firstDay->format('M');
                                            $lastMonth = $firstDay->format('M');
                                        }
                                    @endphp
                                    <div class="flex flex-col shrink-0" style="gap: 3px; position: relative;">
                                        {{-- Month Label at top of column --}}
                                        @if($monthName)
                                            <span class="absolute font-sans text-zinc-500 font-medium" style="top: -18px; left: 0; font-size: 9px; white-space: nowrap;">{{ $monthName }}</span>
                                        @endif
                                        
                                        @foreach($dayOrder as $dIdx)
                                            @php
                                                $day = $days[$dIdx] ?? null;
                                                $count = $day ? $day['count'] : 0;
                                                
                                                // GitHub exact color hexes
                                                if ($count == 0) {
                                                    $bgColor = '#ebedf0';
                                                } elseif ($count == 1) {
                                                    $bgColor = '#9be9a8';
                                                } elseif ($count == 2) {
                                                    $bgColor = '#40c463';
                                                } elseif ($count == 3) {
                                                    $bgColor = '#30a14e';
                                                } else {
                                                    $bgColor = '#216e39';
                                                }
                                            @endphp
                                            
                                            @if($day)
                                                <div class="group relative shrink-0">
                                                    <div class="transition-colors cursor-pointer" style="width: 10px; height: 10px; border-radius: 2px; background-color: {{ $bgColor }};"></div>
                                                    {{-- Tooltip overlay --}}
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover:block z-50 bg-zinc-900 text-white text-[9px] font-semibold py-1 px-2 rounded shadow-sm whitespace-nowrap leading-none pointer-events-none">
                                                        {{ $count }} {{ Str::plural('application', $count) }} • {{ $day['formattedDate'] }}
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Blank spacer for missing days --}}
                                                <div style="width: 10px; height: 10px; background-color: transparent;"></div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Legend & Footer Row --}}
                        <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-3 select-none">
                            <a href="{{ route('tracker') }}" class="text-[10px] text-zinc-400 hover:text-zinc-650 transition-colors font-medium">
                                Learn how we track application activity
                            </a>
                            
                            <div class="flex items-center" style="gap: 3px;">
                                <span class="mr-1">Less</span>
                                <div style="width: 10px; height: 10px; border-radius: 2px; background-color: #ebedf0;"></div>
                                <div style="width: 10px; height: 10px; border-radius: 2px; background-color: #9be9a8;"></div>
                                <div style="width: 10px; height: 10px; border-radius: 2px; background-color: #40c463;"></div>
                                <div style="width: 10px; height: 10px; border-radius: 2px; background-color: #30a14e;"></div>
                                <div style="width: 10px; height: 10px; border-radius: 2px; background-color: #216e39;"></div>
                                <span class="ml-1">More</span>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Interviews -->
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-4 mt-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-xs font-bold text-zinc-850 tracking-tight uppercase tracking-wider">Upcoming Interviews</h3>
                                <p class="text-[10px] text-zinc-400 font-medium">Your schedule for the next interviews</p>
                            </div>
                            <a href="{{ route('interviews') }}" class="flex items-center gap-1.5 px-2.5 py-1 bg-white border border-zinc-200 hover:bg-zinc-50 rounded-md text-[10px] font-semibold text-zinc-600 transition-colors">
                                <span>View Calendar</span>
                                <i class="ph ph-arrow-right text-[10px]"></i>
                            </a>
                        </div>

                        @if($upcomingInterviews->isEmpty())
                            <div class="flex flex-col items-center justify-center py-8 text-center bg-zinc-50/30 rounded-lg border border-dashed border-zinc-200/80 animate-none">
                                <div class="w-10 h-10 bg-zinc-100 rounded-md flex items-center justify-center text-zinc-400 mb-2.5">
                                    <i class="ph ph-calendar text-lg"></i>
                                </div>
                                <h4 class="text-xs font-bold text-zinc-700">No upcoming interviews</h4>
                                <p class="text-[10px] text-zinc-400 mt-0.5 max-w-[220px]">Take a break or apply to more jobs to schedule an interview!</p>
                            </div>
                        @else
                            <div class="divide-y divide-zinc-150/40">
                                @foreach($upcomingInterviews as $interview)
                                    <div class="flex items-center gap-3.5 py-2.5 group first:pt-0 last:pb-0">
                                        <!-- Date Bubble -->
                                        <div class="shrink-0 w-8 h-8 bg-zinc-50 rounded-md border border-zinc-200/50 flex flex-col items-center justify-center text-zinc-700 font-bold leading-none shadow-2xs">
                                            <span class="text-[11px] font-extrabold leading-none">{{ $interview->interview_date->timezone('Asia/Jakarta')->translatedFormat('d') }}</span>
                                            <span class="text-[7.5px] font-black uppercase tracking-wider mt-0.5 text-zinc-450">{{ $interview->interview_date->timezone('Asia/Jakarta')->translatedFormat('M') }}</span>
                                        </div>
                                        
                                        <!-- Interview Details -->
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between gap-2">
                                                <h4 class="text-[11px] font-bold text-zinc-800 truncate leading-tight">
                                                    {{ $interview->recruitment_stage }} - {{ $interview->position }}
                                                </h4>
                                                <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-zinc-105 text-zinc-500 border border-zinc-200/40 leading-none">
                                                    {{ $interview->interview_type ?? 'N/A' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2 mt-0.5 leading-none">
                                                <p class="text-[9px] text-zinc-450 font-medium leading-none">{{ $interview->company_name }}</p>
                                                @if($interview->interview_location)
                                                    <span class="text-zinc-300 text-[8px] leading-none">•</span>
                                                    <div class="flex items-center gap-0.5 text-[9px] text-zinc-400 font-medium leading-none truncate">
                                                        <i class="ph ph-map-pin"></i>
                                                        <span class="truncate">{{ $interview->interview_location }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Time -->
                                        <div class="shrink-0 text-right">
                                            <p class="text-[11px] font-bold text-zinc-800 leading-tight">
                                                {{ $interview->interview_date->timezone('Asia/Jakarta')->translatedFormat('H:i') }}
                                            </p>
                                            <p class="text-[7.5px] text-zinc-450 font-bold uppercase tracking-wider leading-none">
                                                {{ $interview->interview_date->timezone('Asia/Jakarta')->translatedFormat('T') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Quick Actions (col-span 1) -->
                <div class="col-span-1 h-full">

                    <!-- Quick Actions Panel -->
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-4 h-full flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3.5 select-none">
                            <div>
                                <h3 class="text-xs font-bold text-zinc-850 tracking-tight uppercase tracking-wider">Quick Actions</h3>
                                <p class="text-[9px] text-zinc-400 font-medium mt-0.5">Shortcuts to accelerate your search</p>
                            </div>
                            <i class="ph ph-lightning text-amber-500 text-sm"></i>
                        </div>

                        <div class="space-y-1 flex-1 flex flex-col justify-center">
                            <!-- Action 1: Add App -->
                            <button onclick="openJobModal()" class="w-full flex items-center justify-between p-2 hover:bg-zinc-50 rounded-md group transition-all text-left">
                                <div class="flex items-center gap-2.5">
                                    <i class="ph ph-plus text-zinc-400 group-hover:text-zinc-700 transition-colors text-sm"></i>
                                    <span class="text-[11px] font-semibold text-zinc-650 group-hover:text-zinc-900 transition-colors">Add New Application</span>
                                </div>
                                <i class="ph ph-caret-right text-[10px] text-zinc-300 group-hover:text-zinc-500 transition-transform group-hover:translate-x-0.5"></i>
                            </button>

                            <!-- Action 2: CV Builder -->
                            <a href="{{ route('cv.builder') }}" class="w-full flex items-center justify-between p-2 hover:bg-zinc-50 rounded-md group transition-all text-left">
                                <div class="flex items-center gap-2.5">
                                    <i class="ph ph-file-text text-zinc-400 group-hover:text-zinc-700 transition-colors text-sm"></i>
                                    <span class="text-[11px] font-semibold text-zinc-650 group-hover:text-zinc-900 transition-colors">CV Builder & Editor</span>
                                </div>
                                <i class="ph ph-caret-right text-[10px] text-zinc-300 group-hover:text-zinc-500 transition-transform group-hover:translate-x-0.5"></i>
                            </a>

                            <!-- Action 3: AI Analyzer -->
                            <a href="{{ route('ai-analyzer.index') }}" class="w-full flex items-center justify-between p-2 hover:bg-zinc-50 rounded-md group transition-all text-left">
                                <div class="flex items-center gap-2.5">
                                    <i class="ph ph-sparkle text-zinc-400 group-hover:text-zinc-700 transition-colors text-sm"></i>
                                    <span class="text-[11px] font-semibold text-zinc-650 group-hover:text-zinc-900 transition-colors">AI CV & JD Analyzer</span>
                                </div>
                                <i class="ph ph-caret-right text-[10px] text-zinc-300 group-hover:text-zinc-500 transition-transform group-hover:translate-x-0.5"></i>
                            </a>

                            <!-- Action 4: Cover Letter -->
                            <a href="{{ route('cover-letters.index') }}" class="w-full flex items-center justify-between p-2 hover:bg-zinc-50 rounded-md group transition-all text-left">
                                <div class="flex items-center gap-2.5">
                                    <i class="ph ph-envelope-simple text-zinc-400 group-hover:text-zinc-700 transition-colors text-sm"></i>
                                    <span class="text-[11px] font-semibold text-zinc-650 group-hover:text-zinc-900 transition-colors">Cover Letter Generator</span>
                                </div>
                                <i class="ph ph-caret-right text-[10px] text-zinc-300 group-hover:text-zinc-500 transition-transform group-hover:translate-x-0.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('modals')
    <!-- Ultra Compact Floating Modal -->
    <div id="jobModal" class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs hidden z-[99999] flex items-center justify-center p-4 transition-all duration-300">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200 transform transition-all animate-modal-in">
            <!-- Modal Header: Clean White -->
            <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/60 flex items-center justify-center shadow-3xs">
                        <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-4 h-4 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h3 class="text-xs font-bold text-zinc-800 tracking-tight" id="modalTitle">New Opportunity</h3>
                            <span id="modalBadge" class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/60 leading-none">Tracking</span>
                        </div>
                        <p class="text-zinc-400 text-[9px] font-medium mt-0.5">Career Growth Tracking</p>
                    </div>
                </div>
                <button onclick="closeJobModal()" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 transition-all text-zinc-400 hover:text-zinc-800">
                    <i class="ph ph-x text-sm"></i>
                </button>
            </div>
            
            <div class="p-4 bg-white overflow-y-auto custom-scrollbar">
                @livewire('job-application-form')
            </div>
        </div>
    </div>
    @endpush

    @push('styles')
    <style>
        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-modal-in {
            animation: modalIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
    @endpush

    <script>
        window.addEventListener('edit-job', event => {
            document.getElementById('modalTitle').innerText = 'Edit Opportunity';
            document.getElementById('modalBadge').innerText = 'Edit';
            window.openJobModal(true);
        });

        window.openJobModal = function(isEdit = false) {
            const modal = document.getElementById('jobModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            if (!isEdit) {
                Livewire.dispatch('resetFormForNewJob');
            }
        };

        window.closeJobModal = function() {
            const modal = document.getElementById('jobModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            // Reset modal title and badge
            document.getElementById('modalTitle').innerText = 'New Opportunity';
            document.getElementById('modalBadge').innerText = 'Tracking';
            // Reset form when closing for fresh state on "Add"
            Livewire.dispatch('resetFormForNewJob');
        };

        window.addEventListener('job-saved', event => {
            window.closeJobModal();
            window.location.reload();
        });
    </script>
</x-app-layout>