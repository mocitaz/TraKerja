<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Job <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Detail</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Opportunity Insights</p>
        </div>
    </x-slot>

    @php
        // Pipeline Stepper Logic
        $pipelineStages = [
            ['label' => 'Applied', 'icon' => 'ph-paper-plane-tilt', 'stages' => ['Applied', 'Follow Up']],
            ['label' => 'Assessment', 'icon' => 'ph-exam', 'stages' => ['Assessment Test', 'Psychotest']],
            ['label' => 'Interview', 'icon' => 'ph-chats-circle', 'stages' => ['HR - Interview', 'User - Interview', 'LGD', 'Presentation Round']],
            ['label' => 'Offering', 'icon' => 'ph-handshake', 'stages' => ['Offering']],
            ['label' => 'Result', 'icon' => 'ph-flag-checkered', 'stages' => ['Accepted', 'Declined', 'Not Processed']]
        ];

        $currentStageGroupIndex = 0;
        foreach ($pipelineStages as $index => $group) {
            if (in_array($job->recruitment_stage, $group['stages'])) {
                $currentStageGroupIndex = $index;
                break;
            }
        }
        
        $editUrl = route('tracker') . '?edit=' . $job->id;
    @endphp

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="min-h-screen bg-[#f8fafc] pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('tracker') }}" class="inline-flex items-center gap-2 text-xs font-black text-slate-400 hover:text-indigo-600 transition-colors group">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Back to Job Tracker
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Premium Hero Card -->
                    <div class="relative overflow-hidden bg-white rounded-[2.5rem] border border-slate-200/60 shadow-[0_20px_50px_rgba(0,0,0,0.04)]">
                        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
                        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 bg-violet-50 rounded-full blur-2xl opacity-50"></div>

                        <div class="relative p-6">
                            <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                                <div class="w-24 h-24 shrink-0 relative group">
                                    <div class="absolute inset-2 bg-indigo-600/20 blur-2xl rounded-full group-hover:scale-125 transition-transform duration-500"></div>
                                    <div class="relative w-full h-full bg-white rounded-[2rem] border border-slate-200/60 shadow-xl overflow-hidden flex items-center justify-center p-1.5">
                                        <div class="w-full h-full rounded-[1.6rem] relative overflow-hidden flex items-center justify-center bg-slate-950">
                                            <svg class="absolute inset-0 w-full h-full opacity-40" viewBox="0 0 100 100" preserveAspectRatio="none">
                                                <defs>
                                                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                                                        <stop offset="0%" style="stop-color:#4f46e5;stop-opacity:1" />
                                                        <stop offset="100%" style="stop-color:#9333ea;stop-opacity:1" />
                                                    </linearGradient>
                                                </defs>
                                                <circle cx="20" cy="20" r="40" fill="url(#grad1)" />
                                                <circle cx="90" cy="80" r="30" fill="#6366f1" />
                                                <rect x="50" y="-10" width="60" height="60" rx="10" transform="rotate(25)" fill="#4338ca" />
                                            </svg>
                                            <div class="absolute inset-0 border-[8px] border-white/5 rounded-full scale-110 blur-sm"></div>
                                            <span class="relative z-10 text-white text-4xl font-black tracking-tighter select-none">
                                                {{ substr($job->company_name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex-1 space-y-3 w-full">
                                    <div class="space-y-0.5">
                                        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">{{ $job->position }}</h1>
                                        <p class="text-lg font-bold text-indigo-600 italic tracking-tight">{{ $job->company_name }}</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                            <i class="ph-bold ph-map-pin"></i>
                                            {{ $job->location ?? 'Remote' }}
                                        </span>
                                        <span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                            <i class="ph-bold ph-globe"></i>
                                            {{ $job->platform }}
                                        </span>
                                        <span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                            <i class="ph-bold ph-medal"></i>
                                            {{ $job->career_level ?? 'Not Set' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Pipeline -->
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-200/60 shadow-sm overflow-hidden relative">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <i class="ph-bold ph-git-merge text-indigo-500 text-sm"></i>
                            Recruitment Pipeline
                        </h3>
                        <div class="relative">
                            <div class="absolute top-1/2 left-0 right-0 h-1 bg-slate-100 -translate-y-1/2 rounded-full hidden sm:block"></div>
                            <div class="absolute top-1/2 left-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 -translate-y-1/2 rounded-full hidden sm:block transition-all duration-700" 
                                 style="width: {{ $currentStageGroupIndex > 0 ? ($currentStageGroupIndex / (count($pipelineStages) - 1)) * 100 : 0 }}%"></div>
                            
                            <div class="relative flex flex-col sm:flex-row justify-between gap-4 sm:gap-0">
                                @foreach($pipelineStages as $index => $stage)
                                    @php
                                        $isPast = $index < $currentStageGroupIndex;
                                        $isCurrent = $index === $currentStageGroupIndex;
                                        $isFuture = $index > $currentStageGroupIndex;
                                        
                                        if ($isPast) {
                                            $iconBg = 'bg-indigo-600 text-white shadow-md shadow-indigo-200';
                                            $textClass = 'text-indigo-600';
                                        } elseif ($isCurrent) {
                                            $iconBg = 'bg-white text-indigo-600 border-2 border-indigo-600 shadow-[0_0_15px_rgba(79,70,229,0.4)] animate-pulse';
                                            $textClass = 'text-slate-900 font-black';
                                        } else {
                                            $iconBg = 'bg-slate-100 text-slate-400 border border-slate-200';
                                            $textClass = 'text-slate-400';
                                        }

                                        if ($isCurrent && $stage['label'] === 'Result') {
                                            if ($job->application_status === 'Accepted') {
                                                $iconBg = 'bg-emerald-500 text-white shadow-[0_0_15px_rgba(16,185,129,0.4)]';
                                                $textClass = 'text-emerald-600 font-black';
                                            } elseif ($job->application_status === 'Declined') {
                                                $iconBg = 'bg-rose-500 text-white shadow-[0_0_15px_rgba(244,63,94,0.4)]';
                                                $textClass = 'text-rose-600 font-black';
                                            }
                                        }
                                    @endphp
                                    <div class="flex sm:flex-col items-center gap-3 sm:gap-2 relative z-10 group">
                                        @if(!$loop->first)
                                        <div class="absolute left-[1.1rem] top-[-1rem] bottom-full w-0.5 bg-slate-100 sm:hidden"></div>
                                        @if($isPast || $isCurrent)
                                        <div class="absolute left-[1.1rem] top-[-1rem] bottom-full w-0.5 bg-indigo-500 sm:hidden"></div>
                                        @endif
                                        @endif
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 transition-all duration-500 {{ $iconBg }}">
                                            <i class="ph-bold {{ $stage['icon'] }} {{ $isCurrent ? 'scale-110' : '' }}"></i>
                                        </div>
                                        <div class="text-left sm:text-center">
                                            <p class="text-[10px] uppercase tracking-widest font-bold {{ $textClass }}">{{ $stage['label'] }}</p>
                                            @if($isCurrent)
                                            <p class="text-[9px] text-slate-500 font-medium italic mt-0.5">{{ $job->recruitment_stage }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Application Status & Additional Notes -->
                    <div class="space-y-6">
                        <!-- Application Status -->
                        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-200/60 shadow-sm group hover:border-indigo-200 transition-all">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                                <i class="ph-bold ph-activity text-indigo-500"></i> Application Status
                            </h3>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-indigo-50 text-indigo-600 group-hover:scale-110 transition-transform shadow-inner">
                                    <i class="ph-bold ph-info text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xl font-black text-slate-900 tracking-tight">{{ $job->application_status }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase">{{ $job->recruitment_stage ?? 'Initial Stage' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="bg-white rounded-[1.5rem] p-7 border border-slate-200/60 shadow-sm">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-5 flex items-center gap-2">
                                <i class="ph-bold ph-note-pencil text-indigo-500"></i> Additional Notes
                            </h3>
                            <div class="prose prose-slate max-w-none">
                                @if($job->notes)
                                    <p class="text-xs text-slate-600 leading-relaxed font-medium italic">"{{ $job->notes }}"</p>
                                @else
                                    <p class="text-xs text-slate-300 italic font-medium">No additional notes added...</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Actions & Details -->
                <div class="space-y-6">
                    <!-- Edit Button Action -->
                    <a href="{{ $editUrl }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl transition-all font-black text-sm shadow-xl shadow-indigo-100 active:scale-95 group">
                        <i class="ph-bold ph-pencil-simple text-xl group-hover:rotate-12 transition-transform"></i>
                        Edit Job Details
                    </a>

                    <!-- Next Interview Spotlight -->
                    @if($job->interview_date)
                    @php
                        $now = \Carbon\Carbon::now();
                        $isPast = $job->interview_date->isPast();
                        $diff = $job->interview_date->diffForHumans();
                    @endphp
                    <div class="relative overflow-hidden bg-slate-900 rounded-[1.5rem] p-6 text-white shadow-2xl shadow-indigo-200">
                        <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
                        <h3 class="relative z-10 text-[9px] font-black text-indigo-300 uppercase tracking-[0.3em] mb-4">Next Interview</h3>
                        @if(!$isPast)
                        <div class="relative z-10 mb-5 inline-flex items-center gap-2 px-3 py-1.5 bg-rose-500/20 border border-rose-500/30 rounded-lg">
                            <div class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></div>
                            <span class="text-[10px] font-black text-rose-200 tracking-widest uppercase">{{ $diff }}</span>
                        </div>
                        @endif
                        <div class="relative z-10 space-y-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex flex-col items-center justify-center border border-white/10">
                                    <span class="text-[9px] font-black uppercase text-indigo-200">{{ $job->interview_date->format('M') }}</span>
                                    <span class="text-lg font-black">{{ $job->interview_date->format('d') }}</span>
                                </div>
                                <div>
                                    <p class="text-base font-black tracking-tight">{{ $job->interview_date->format('H:i') }} WIB</p>
                                    <p class="text-[9px] font-bold text-indigo-300/80 uppercase">{{ $job->interview_type ?? 'Meeting' }}</p>
                                </div>
                            </div>
                            @if($job->interview_location)
                            <div class="pt-4 border-t border-white/10 flex items-start gap-3">
                                <i class="ph-bold ph-map-pin text-indigo-400 shrink-0 mt-0.5"></i>
                                <span class="text-[10px] font-bold text-indigo-100 leading-relaxed">{{ $job->interview_location }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Company Research -->
                    <div class="bg-white rounded-[1.5rem] p-6 border border-slate-200/60 shadow-sm space-y-4">
                        <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                            <i class="ph-bold ph-magnifying-glass text-blue-500"></i> Research Company
                        </h3>
                        <div class="space-y-2">
                            <a href="https://www.linkedin.com/search/results/companies/?keywords={{ urlencode($job->company_name) }}" target="_blank" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-[#0077b5]/10 text-[#0077b5] flex items-center justify-center">
                                        <i class="ph-bold ph-linkedin-logo text-lg"></i>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-700 group-hover:text-[#0077b5] transition-colors">Search on LinkedIn</span>
                                </div>
                                <i class="ph-bold ph-arrow-up-right text-slate-400 group-hover:text-[#0077b5] transition-colors"></i>
                            </a>
                            <a href="https://www.google.com/search?q={{ urlencode($job->company_name . ' company reviews') }}" target="_blank" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                        <i class="ph-bold ph-google-logo text-lg"></i>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-700 group-hover:text-emerald-600 transition-colors">Google Reviews</span>
                                </div>
                                <i class="ph-bold ph-arrow-up-right text-slate-400 group-hover:text-emerald-600 transition-colors"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Original Job Post -->
                    @if($job->platform_link)
                    <a href="{{ $job->platform_link }}" target="_blank" class="flex items-center justify-between p-5 bg-white border border-slate-200/60 rounded-[1.5rem] text-slate-700 hover:bg-slate-50 hover:border-indigo-200 transition-all shadow-sm group">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="ph-bold ph-link-simple text-lg"></i>
                            </div>
                            <span class="text-[11px] font-black uppercase tracking-widest">Original Job Post</span>
                        </div>
                        <i class="ph-bold ph-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    @endif

                    <!-- Timeline Log -->
                    <div class="bg-white rounded-[1.5rem] p-6 border border-slate-200/60 shadow-sm space-y-6">
                        <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                            <i class="ph-bold ph-clock-counter-clockwise"></i> Timeline Log
                        </h3>
                        <div class="space-y-5">
                            <div class="flex gap-4">
                                <div class="w-[1.5px] bg-slate-100 relative">
                                    <div class="absolute top-0 -left-[4px] w-2.5 h-2.5 rounded-full bg-indigo-600 border-[3px] border-white shadow-sm"></div>
                                </div>
                                <div class="pb-1">
                                    <p class="text-[10px] font-black text-slate-900 uppercase">Application Sent</p>
                                    <p class="text-[9px] font-bold text-slate-400 mt-0.5 italic">{{ $job->application_date->format('d F Y') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-[1.5px] bg-slate-100 relative">
                                    <div class="absolute top-0 -left-[4px] w-2.5 h-2.5 rounded-full bg-slate-300 border-[3px] border-white shadow-sm"></div>
                                </div>
                                <div class="pb-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase">Current Status</p>
                                    <p class="text-[9px] font-bold text-indigo-600 mt-0.5 italic uppercase tracking-wider">{{ $job->application_status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
