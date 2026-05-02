<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-600/10 border border-primary-600/20 flex items-center justify-center">
                <i class="ph-fill ph-briefcase text-xl text-primary-600"></i>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-900 tracking-tight">Job Detail</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Opportunity Insights & Tracking</p>
            </div>
        </div>
    </x-slot>

    @php
        // Pipeline Stepper Logic - Refined to include status checks
        $pipelineStages = [
            ['label' => 'Applied', 'icon' => 'ph-paper-plane-tilt', 'stages' => ['Applied', 'Follow Up']],
            ['label' => 'Assessment', 'icon' => 'ph-exam', 'stages' => ['Assessment', 'Assessment Test', 'Psychotest']],
            ['label' => 'Interview', 'icon' => 'ph-chats-circle', 'stages' => ['Interview', 'HR - Interview', 'User - Interview', 'LGD', 'Presentation Round']],
            ['label' => 'Offering', 'icon' => 'ph-handshake', 'stages' => ['Offering']],
            ['label' => 'Result', 'icon' => 'ph-flag-checkered', 'stages' => ['Accepted', 'Declined', 'Not Processed']]
        ];

        $currentStageGroupIndex = 0;
        
        // If application_status is Final (Accepted/Declined), jump to the last stage immediately
        if (in_array($job->application_status, ['Accepted', 'Declined'])) {
            $currentStageGroupIndex = 4;
        } else {
            foreach ($pipelineStages as $index => $group) {
                if (in_array($job->recruitment_stage, $group['stages']) || in_array($job->application_status, $group['stages'])) {
                    $currentStageGroupIndex = $index;
                    break;
                }
            }
        }
        
        $editUrl = route('tracker') . '?edit=' . $job->id;
    @endphp

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(241, 245, 249, 0.5);
        }
        
        .pipeline-line {
            background: #f1f5f9;
            height: 2px;
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            z-index: 0;
        }

        .pipeline-progress {
            height: 2px;
            position: absolute;
            top: 20px;
            left: 0;
            z-index: 0;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bento-inner {
            background: white;
            border-radius: 1.5rem;
            border: 1px solid #f1f5f9;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .bento-inner:hover {
            border-color: #e2e8f0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
    </style>

    <div class="min-h-screen bg-[#fcfcfd] pb-24">
        <div class="max-w-[1100px] mx-auto px-4 pt-10">
            
            {{-- ── Breadcrumb ── --}}
            <div class="mb-8">
                <a href="{{ route('tracker') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-primary-600 uppercase tracking-widest transition-all group">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Back to Tracker
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- ── Left Side: Identity & Pipeline ── --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- Job Identity Card --}}
                    <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden shadow-sm">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
                        
                        <div class="relative flex flex-col md:flex-row gap-8 items-start">
                            <div class="w-20 h-20 rounded-[2rem] bg-slate-900 flex items-center justify-center shadow-2xl shadow-slate-200 shrink-0">
                                <span class="text-white text-3xl font-black">{{ substr($job->company_name, 0, 1) }}</span>
                            </div>
                            
                            <div class="flex-1 space-y-4">
                                <div>
                                    <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none mb-2">{{ $job->position }}</h1>
                                    <div class="flex items-center gap-2">
                                        <p class="text-lg font-bold text-primary-600 italic tracking-tight">{{ $job->company_name }}</p>
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                        <p class="text-sm font-medium text-slate-400">{{ $job->location ?? 'Remote' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex flex-wrap gap-2">
                                    <div class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl flex items-center gap-2">
                                        <i class="ph-fill ph-globe text-primary-500"></i>
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $job->platform }}</span>
                                    </div>
                                    <div class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl flex items-center gap-2">
                                        <i class="ph-fill ph-calendar text-primary-500"></i>
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $job->application_date->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ $editUrl }}" class="shrink-0 p-3 rounded-2xl bg-white border border-slate-200 text-slate-400 hover:text-primary-600 hover:border-primary-200 transition-all shadow-sm">
                                <i class="ph-bold ph-pencil-simple text-xl"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Pipeline Visualization --}}
                    <div class="glass-card p-10 rounded-[2.5rem] shadow-sm">
                        <div class="flex items-center justify-between mb-12">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                                <i class="ph-bold ph-git-merge text-primary-500"></i> Recruitment Pipeline
                            </h3>
                            <div class="px-3 py-1 bg-primary-50 text-primary-600 rounded-full text-[9px] font-black uppercase tracking-widest">
                                {{ $job->recruitment_stage }}
                            </div>
                        </div>

                        <div class="relative px-2">
                            <div class="pipeline-line"></div>
                            <div class="pipeline-progress bg-primary-500 shadow-[0_0_15px_rgba(99,102,241,0.4)]" 
                                 style="width: {{ $currentStageGroupIndex > 0 ? ($currentStageGroupIndex / (count($pipelineStages) - 1)) * 100 : 0 }}%"></div>
                            
                            <div class="relative flex justify-between">
                                @foreach($pipelineStages as $index => $stage)
                                    @php
                                        $isPast = $index < $currentStageGroupIndex;
                                        $isCurrent = $index === $currentStageGroupIndex;
                                        
                                        $circleClass = $isPast ? 'bg-primary-600 text-white' : ($isCurrent ? 'bg-white border-4 border-primary-600 scale-110 shadow-lg' : 'bg-white border-2 border-slate-100 text-slate-300');
                                        
                                        // Final Status Override
                                        if ($isCurrent && $index === 4) {
                                            if ($job->application_status === 'Accepted') $circleClass = 'bg-emerald-500 text-white border-4 border-emerald-100 scale-110 shadow-lg';
                                            elseif ($job->application_status === 'Declined') $circleClass = 'bg-rose-500 text-white border-4 border-rose-100 scale-110 shadow-lg';
                                        }
                                    @endphp
                                    <div class="flex flex-col items-center gap-4 group">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center relative z-10 transition-all duration-500 {{ $circleClass }}">
                                            <i class="ph-bold {{ $stage['icon'] }} text-sm"></i>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-widest {{ $isCurrent ? 'text-slate-900' : 'text-slate-400' }}">{{ $stage['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Bento Information --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bento-inner space-y-4">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Application Status</h3>
                            <div class="flex items-center gap-4">
                                <div @class([
                                    'w-12 h-12 rounded-2xl flex items-center justify-center text-xl',
                                    'bg-emerald-50 text-emerald-600' => $job->application_status === 'Accepted',
                                    'bg-rose-50 text-rose-600' => $job->application_status === 'Declined',
                                    'bg-blue-50 text-blue-600' => $job->application_status === 'On Process',
                                ])>
                                    <i class="ph-fill ph-info"></i>
                                </div>
                                <div>
                                    <p class="text-xl font-black text-slate-900 tracking-tight">{{ $job->application_status }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Current Milestone</p>
                                </div>
                            </div>
                        </div>

                        <div class="bento-inner space-y-4">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Additional Notes</h3>
                            <p class="text-xs text-slate-600 leading-relaxed font-medium italic">
                                {{ $job->notes ? '"'.$job->notes.'"' : 'No additional notes provided for this opportunity.' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Right Side: Actions & Deadlines ── --}}
                <div class="lg:col-span-4 space-y-8">
                    
                    {{-- Interview Spotlight --}}
                    @if($job->interview_date)
                        @php
                            $isPast = $job->interview_date->isPast();
                            $diff = $job->interview_date->diffForHumans();
                        @endphp
                        <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-primary-500/20 rounded-full blur-2xl -mr-16 -mt-16"></div>
                            
                            <h3 class="text-[10px] font-black text-primary-400 uppercase tracking-[0.3em] mb-6 relative z-10">Next Interview</h3>
                            
                            <div class="space-y-6 relative z-10">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md flex flex-col items-center justify-center border border-white/10">
                                        <span class="text-[10px] font-black uppercase text-primary-300">{{ $job->interview_date->format('M') }}</span>
                                        <span class="text-2xl font-black">{{ $job->interview_date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="text-xl font-black leading-tight">{{ $job->interview_date->format('H:i') }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $job->interview_type ?? 'Meeting' }}</p>
                                    </div>
                                </div>

                                @if(!$isPast)
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 border border-emerald-500/30 rounded-lg">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-emerald-200">{{ $diff }}</span>
                                    </div>
                                @endif

                                @if($job->interview_location)
                                    <div class="pt-6 border-t border-white/5 space-y-2">
                                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Location / Link</p>
                                        <p class="text-xs font-medium text-slate-300 break-all leading-relaxed">{{ $job->interview_location }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Research Panel --}}
                    <div class="glass-card p-8 rounded-[2.5rem] space-y-6 shadow-sm">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Research Company</h3>
                        <div class="space-y-3">
                            <a href="https://www.linkedin.com/search/results/companies/?keywords={{ urlencode($job->company_name) }}" target="_blank" class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-primary-200 hover:bg-primary-50/30 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-[#0077b5]/10 flex items-center justify-center text-[#0077b5]">
                                        <i class="ph-bold ph-linkedin-logo text-lg"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">LinkedIn Search</span>
                                </div>
                                <i class="ph-bold ph-arrow-up-right text-slate-300 group-hover:text-primary-600 transition-colors"></i>
                            </a>
                            
                            @if($job->platform_link)
                            <a href="{{ $job->platform_link }}" target="_blank" class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-primary-200 hover:bg-primary-50/30 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600">
                                        <i class="ph-bold ph-link-simple text-lg"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">Original Post</span>
                                </div>
                                <i class="ph-bold ph-arrow-up-right text-slate-300 group-hover:text-primary-600 transition-colors"></i>
                            </a>
                            @endif
                        </div>
                    </div>

                    {{-- Audit Trail --}}
                    <div class="glass-card p-8 rounded-[2.5rem] space-y-6 shadow-sm">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Application Timeline</h3>
                        <div class="space-y-6 relative before:absolute before:left-[7px] before:top-2 before:bottom-2 before:w-px before:bg-slate-100">
                            <div class="relative pl-7">
                                <div class="absolute left-0 top-1 w-[14px] h-[14px] rounded-full bg-primary-600 border-4 border-white shadow-sm"></div>
                                <p class="text-[10px] font-black text-slate-900 uppercase">Applied</p>
                                <p class="text-[9px] font-bold text-slate-400">{{ $job->application_date->format('d M Y') }}</p>
                            </div>
                            <div class="relative pl-7">
                                <div class="absolute left-0 top-1 w-[14px] h-[14px] rounded-full bg-slate-200 border-4 border-white shadow-sm"></div>
                                <p class="text-[10px] font-black text-slate-400 uppercase">Last Updated</p>
                                <p class="text-[9px] font-bold text-slate-400">{{ $job->updated_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
