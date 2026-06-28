<x-app-layout>
    <x-slot name="header">
        <!-- Ignored for consistency -->
    </x-slot>

    @php
        // Pipeline Stepper Logic - Refined to include status checks
        $pipelineStages = [
            ['label' => 'Applied', 'icon' => 'ph-bold ph-paper-plane-tilt', 'stages' => ['Applied', 'Follow Up']],
            ['label' => 'Assessment', 'icon' => 'ph-bold ph-exam', 'stages' => ['Assessment', 'Assessment Test', 'Psychotest']],
            ['label' => 'Interview', 'icon' => 'ph-bold ph-chats-circle', 'stages' => ['Interview', 'HR - Interview', 'User - Interview', 'LGD', 'Presentation Round']],
            ['label' => 'Offering', 'icon' => 'ph-bold ph-handshake', 'stages' => ['Offering']],
            ['label' => 'Result', 'icon' => 'ph-bold ph-flag-checkered', 'stages' => ['Accepted', 'Declined', 'Not Processed']]
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

        // UMK 2026 Salary Benchmark Data Lookup
        $matchedUmk = null;
        $jsonPath = base_path('data_umk_lengkap_2026.json');
        if (file_exists($jsonPath)) {
            $provincesData = json_decode(file_get_contents($jsonPath), true) ?: [];
            $searchLoc = strtoupper(trim($job->location ?? ''));
            $cleanSearch = preg_replace('/^(KOTA|KABUPATEN|KAB\.)\s+/i', '', $searchLoc);
            
            foreach ($provincesData as $prov) {
                foreach ($prov['daftar_wilayah'] as $wilayah) {
                    $cleanWilayah = preg_replace('/^(KOTA|KABUPATEN|KAB\.)\s+/i', '', strtoupper($wilayah['nama_wilayah']));
                    if ($cleanSearch && (str_contains($cleanWilayah, $cleanSearch) || str_contains($cleanSearch, $cleanWilayah))) {
                        $matchedUmk = $wilayah;
                        $matchedUmk['provinsi'] = $prov['nama_provinsi'];
                        break 2;
                    }
                }
            }
            
            // Default fallback to Jakarta if not matched or remote
            if (!$matchedUmk && !empty($provincesData)) {
                foreach ($provincesData as $prov) {
                    if (str_contains(strtoupper($prov['nama_provinsi']), 'JAKARTA')) {
                        $matchedUmk = $prov['daftar_wilayah'][0] ?? null;
                        if ($matchedUmk) $matchedUmk['provinsi'] = $prov['nama_provinsi'];
                        break;
                    }
                }
            }
        }

        // Realistic salary estimation algorithm based on position and career level
        $salaryEstimate = null;
        if ($matchedUmk) {
            $pos = strtolower(trim($job->position ?? ''));
            $lvl = strtolower(trim($job->career_level ?? ''));
            $minimumSalary = $matchedUmk['minimum_salary'];
            
            // Determine base multiplier based on role category
            $minMult = 1.1;
            $maxMult = 1.8;
            
            // High-Paying Tech/IT roles
            if (preg_match('/(developer|engineer|programmer|software|backend|frontend|fullstack|data scientist|analyst|devops|system administrator|sysadmin|network|it|cyber|security|qa|testing|scrum|product manager|ui\/ux|data analyst)/i', $pos)) {
                $minMult = 1.6;
                $maxMult = 3.0;
            }
            // Management / Leadership roles
            elseif (preg_match('/(manager|lead|head|director|vp|vice president|ceo|cto|cfo|coo|chief|supervisor|spv)/i', $pos)) {
                $minMult = 1.8;
                $maxMult = 3.5;
            }
            // Entry / Admin / Services roles
            elseif (preg_match('/(admin|clerk|data entry|customer service|cs|call center|receptionist|cashier|operator|staff|officer|assistant|driver|courier|security|cleaning|waiter|magang|intern)/i', $pos)) {
                $minMult = 1.0;
                $maxMult = 1.35;
            }
            // Standard professional roles
            elseif (preg_match('/(marketing|sales|hr|human resources|finance|accountant|accounting|consultant|legal|lawyer|designer|writer|copywriter|content creator)/i', $pos)) {
                $minMult = 1.2;
                $maxMult = 2.0;
            }
            
            // Adjust based on seniority keywords in title
            if (preg_match('/(senior|sr\.|lead|head|principal)/i', $pos)) {
                $minMult *= 1.5;
                $maxMult *= 2.0;
            } elseif (preg_match('/(junior|jr\.|entry|associate|assistant)/i', $pos)) {
                $minMult *= 0.8;
                $maxMult *= 1.0;
            }
            
            // Adjust based on career level field
            if ($lvl === 'intern') {
                $minMult = 0.3;
                $maxMult = 0.7;
            } elseif ($lvl === 'freelance') {
                $minMult *= 0.7;
                $maxMult *= 1.1;
            }
            
            // Ensure logical constraints
            if ($minMult > $maxMult) {
                $temp = $minMult;
                $minMult = $maxMult;
                $maxMult = $temp;
            }
            
            // Prevent multipliers from dropping below reasonable bounds
            if ($lvl !== 'intern' && !str_contains($pos, 'intern') && !str_contains($pos, 'magang')) {
                $minMult = max(1.0, $minMult);
                $maxMult = max(1.15, $maxMult);
            } else {
                $minMult = max(0.25, $minMult);
                $maxMult = max(0.5, $maxMult);
            }
            
            $salaryEstimate = [
                'min' => $minimumSalary * $minMult,
                'max' => $minimumSalary * $maxMult
            ];
        }
    @endphp

    <div class="bg-[#fafafa] min-h-screen pb-16">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            <!-- Breadcrumbs -->
            <div class="mb-4">
                <a href="{{ route('tracker') }}" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-zinc-500 hover:text-zinc-800 uppercase tracking-wider transition-colors">
                    <i class="ph-bold ph-arrow-left text-xs"></i>
                    <span>Back to Tracker</span>
                </a>
            </div>

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph-bold ph-briefcase text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">Opportunity Detail</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60">Insight</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Track, edit, and research details for this specific application.</p>
                    </div>
                </div>

                <a href="{{ $editUrl }}" class="inline-flex items-center justify-center gap-1.5 h-[30px] px-3 bg-primary-50 hover:bg-primary-100 border border-primary-200/60 text-zinc-800 text-[11px] font-bold rounded-md shadow-3xs transition-all duration-150 active:scale-97 hover:shadow-2xs shrink-0 focus:outline-none uppercase tracking-wider">
                    <i class="ph-bold ph-pencil-simple text-xs"></i>
                    <span>Edit Opportunity</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                
                {{-- ── Left Side: Main Consolidated Detail Sheet ── --}}
                <div class="lg:col-span-8 bg-white border border-zinc-200/60 rounded-lg shadow-3xs overflow-hidden">
                    
                    {{-- Header / Identity Area --}}
                    <div class="p-6 border-b border-zinc-100 bg-zinc-50/20">
                        <div class="flex flex-col sm:flex-row gap-6 items-center">
                            <div class="w-12 h-12 rounded-lg bg-zinc-100 border border-zinc-200/60 flex items-center justify-center text-zinc-500 shrink-0">
                                <i class="ph-bold ph-buildings text-xl"></i>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h2 class="text-lg font-bold text-zinc-900 tracking-tight leading-snug mb-0.5">{{ $job->position }}</h2>
                                <p class="text-xs font-bold text-zinc-750 mb-1.5">{{ $job->company_name }}</p>
                                <div class="flex items-center gap-1.5 text-[11px] text-zinc-450 font-medium whitespace-nowrap">
                                    <i class="ph-bold ph-map-pin text-zinc-400"></i>
                                    <span>{{ $job->location ?? 'Remote' }}</span>
                                    <span class="text-zinc-300 mx-1">•</span>
                                    <i class="ph-bold ph-calendar-blank text-zinc-400"></i>
                                    <span>Applied {{ $job->application_date->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($job->isGhosted())
                        {{-- Ghosting Alert --}}
                        <div class="mx-6 mt-6 bg-rose-50/50 border border-rose-100 rounded-lg p-3.5 flex items-start gap-3 animate-fadeIn">
                            <div class="w-7 h-7 rounded-md bg-rose-100 flex items-center justify-center shrink-0 text-rose-500">
                                <i class="ph-bold ph-warning-circle text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-rose-800 mb-0.5">Potential Ghosting detected</h3>
                                <p class="text-[11px] font-medium text-rose-700/95 leading-normal">
                                    It has been over 14 days since you applied without a status update. We suggest sending a <span class="font-bold">Follow-up Email</span> to the recruiter.
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Pipeline Visualizer --}}
                    <div class="p-6 border-b border-zinc-100 bg-white">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="ph-bold ph-git-merge text-zinc-500"></i> Recruitment Pipeline
                            </h3>
                            <span class="px-2 py-0.5 bg-primary-50 border border-primary-100/60 text-zinc-800 rounded text-[9px] font-bold uppercase tracking-wider">
                                {{ $job->recruitment_stage }}
                            </span>
                        </div>

                        <div class="relative px-2">
                            <!-- Progress Lines -->
                            <div class="absolute bg-zinc-100 h-0.5 top-4 left-0 right-0 z-0"></div>
                            <div class="absolute bg-primary-650 h-0.5 top-4 left-0 z-0 transition-all duration-300" 
                                 style="width: {{ $currentStageGroupIndex > 0 ? ($currentStageGroupIndex / (count($pipelineStages) - 1)) * 100 : 0 }}%"></div>
                            
                            <div class="relative flex justify-between">
                                @foreach($pipelineStages as $index => $stage)
                                    @php
                                        $isPast = $index < $currentStageGroupIndex;
                                        $isCurrent = $index === $currentStageGroupIndex;
                                        
                                        $circleClass = $isPast ? 'bg-primary-650 text-white' : ($isCurrent ? 'bg-white border-2 border-primary-650 scale-105 shadow-3xs' : 'bg-white border border-zinc-200 text-zinc-350');
                                        
                                        if ($isCurrent && $index === 4) {
                                            if ($job->application_status === 'Accepted') $circleClass = 'bg-emerald-500 text-white border border-emerald-100 scale-105 shadow-3xs';
                                            elseif ($job->application_status === 'Declined') $circleClass = 'bg-rose-500 text-white border border-rose-100 scale-105 shadow-3xs';
                                        }
                                    @endphp
                                    <div class="flex flex-col items-center gap-1.5 group">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center relative z-10 {{ $circleClass }}">
                                            <i class="{{ $stage['icon'] }} text-xs"></i>
                                        </div>
                                        <span class="text-[9px] font-bold uppercase tracking-wider {{ $isCurrent ? 'text-zinc-800' : 'text-zinc-400' }}">{{ $stage['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Additional Notes --}}
                    <div class="p-6 bg-white">
                        <h3 class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-3">Additional Notes</h3>
                        <div class="bg-zinc-50/50 border border-zinc-200/60 rounded-md p-3.5 border-l-2 border-l-primary-650">
                            <p class="text-xs text-zinc-650 leading-relaxed font-medium italic">
                                {{ $job->notes ? '"'.$job->notes.'"' : 'No additional notes provided for this opportunity.' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Right Side: Sidebar Panel ── --}}
                <div class="lg:col-span-4 space-y-4">
                    
                    {{-- Notion-Style Properties List --}}
                    <div class="bg-white border border-zinc-200/60 p-5 rounded-lg shadow-3xs space-y-4">
                        <h3 class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Properties</h3>
                        
                        <div class="space-y-2.5 text-xs font-semibold">
                            <!-- Platform -->
                            <div class="flex items-center justify-between border-b border-zinc-100 pb-2">
                                <span class="text-zinc-400 font-medium">Platform</span>
                                <span class="text-zinc-800 bg-zinc-50 px-2 py-0.5 border border-zinc-200/60 rounded text-[10px] font-bold uppercase tracking-wider">{{ $job->platform }}</span>
                            </div>
                            
                            <!-- Application Date -->
                            <div class="flex items-center justify-between border-b border-zinc-100 pb-2">
                                <span class="text-zinc-400 font-medium">Applied Date</span>
                                <span class="text-zinc-800 font-bold">{{ $job->application_date->format('d M Y') }}</span>
                            </div>
                            
                            <!-- Career Level -->
                            <div class="flex items-center justify-between border-b border-zinc-100 pb-2">
                                <span class="text-zinc-400 font-medium">Career Level</span>
                                <span class="text-zinc-800 font-bold">{{ $job->career_level ?? 'N/A' }}</span>
                            </div>
                            
                            <!-- Job Link -->
                            <div class="flex items-center justify-between border-b border-zinc-100 pb-2">
                                <span class="text-zinc-400 font-medium">Job Link</span>
                                @if($job->platform_link)
                                    <a href="{{ $job->platform_link }}" target="_blank" class="text-primary-650 hover:underline inline-flex items-center gap-1">
                                        <span>Link</span>
                                        <i class="ph-bold ph-arrow-up-right text-[10px]"></i>
                                    </a>
                                @else
                                    <span class="text-zinc-400">None</span>
                                @endif
                            </div>

                            <!-- App Status -->
                            <div class="flex items-center justify-between">
                                <span class="text-zinc-400 font-medium">Status</span>
                                <span @class([
                                    'px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border',
                                    'bg-emerald-50 text-emerald-800 border-emerald-155' => $job->application_status === 'Accepted',
                                    'bg-rose-50 text-rose-800 border-rose-155' => $job->application_status === 'Declined',
                                    'bg-blue-50 text-blue-800 border-blue-155' => $job->application_status === 'On Process',
                                ])>
                                    {{ $job->application_status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Salary & UMK 2026 Benchmark Card --}}
                    @if($matchedUmk)
                        <div class="bg-white border border-zinc-200/60 p-5 rounded-lg shadow-3xs space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Standar UMK & Gaji 2026</span>
                                <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200/60 rounded text-[8.5px] font-bold uppercase tracking-wider leading-none">Resmi SK 2026</span>
                            </div>
                            
                            <div>
                                <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-wider leading-none">UMK Wilayah</p>
                                <p class="text-base font-extrabold text-zinc-900 tracking-tight mt-1">Rp {{ number_format($matchedUmk['minimum_salary'], 0, ',', '.') }} <span class="text-[10px] text-zinc-450 font-medium">/ bln</span></p>
                                <p class="text-[9.5px] font-semibold text-zinc-500 mt-0.5">{{ $matchedUmk['nama_wilayah'] }} ({{ $matchedUmk['provinsi'] }})</p>
                            </div>

                            <div class="pt-2.5 border-t border-zinc-100 space-y-1">
                                <div class="flex items-center justify-between text-xs font-semibold">
                                    <span class="text-zinc-400 font-medium">Est. Gaji Kompetitif</span>
                                    <span class="text-primary-650 font-bold">Rp {{ number_format($salaryEstimate['min'], 0, ',', '.') }} - Rp {{ number_format($salaryEstimate['max'], 0, ',', '.') }}</span>
                                </div>
                                <p class="text-[8.5px] text-zinc-400 italic leading-snug mt-1">{{ $matchedUmk['sumber_sk'] }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Interview Spotlight --}}
                    @if($job->interview_date)
                        @php
                            $isPast = $job->interview_date->isPast();
                            $diff = $job->interview_date->diffForHumans();
                        @endphp
                        <div class="bg-zinc-900 rounded-lg p-5 text-white relative overflow-hidden shadow-3xs">
                            <h3 class="text-[10px] font-bold text-primary-300 uppercase tracking-widest mb-3.5">Next Interview</h3>
                            
                            <div class="space-y-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded bg-white/10 flex flex-col items-center justify-center border border-white/10 shrink-0">
                                        <span class="text-[8px] font-bold uppercase text-primary-200 leading-none mb-0.5">{{ $job->interview_date->format('M') }}</span>
                                        <span class="text-base font-bold leading-none">{{ $job->interview_date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold leading-none mb-1">{{ $job->interview_date->format('H:i') }}</p>
                                        <p class="text-[9px] font-semibold text-zinc-400 uppercase leading-none">{{ $job->interview_type ?? 'Meeting' }}</p>
                                    </div>
                                </div>

                                @if(!$isPast)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-emerald-500/15 border border-emerald-500/25 rounded">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                        <span class="text-[9px] font-bold uppercase text-emerald-300">{{ $diff }}</span>
                                    </div>
                                @endif

                                @if($job->interview_location)
                                    <div class="pt-3 border-t border-white/10 space-y-1">
                                        <p class="text-[9px] font-bold text-zinc-500 uppercase">Location / Link</p>
                                        <p class="text-[11px] font-medium text-zinc-350 break-all leading-normal">{{ $job->interview_location }}</p>
                                    </div>
                                @endif

                                @if(!$isPast && Auth::user()->canAccessEmailNotifications())
                                    <div class="pt-3 border-t border-white/10">
                                        <form method="POST" action="/jobs/{{ $job->id }}/send-interview-reminder">
                                            @csrf
                                            <button type="submit" class="w-full h-[30px] bg-primary-650 hover:bg-primary-600 text-white rounded-md text-[10px] font-bold transition-all flex items-center justify-center gap-1.5 uppercase tracking-wider">
                                                <i class="ph-bold ph-envelope-simple text-xs"></i>
                                                <span>Send Email Reminder</span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Research Panel --}}
                    <div class="bg-white border border-zinc-200/60 p-5 rounded-lg shadow-3xs space-y-3.5">
                        <h3 class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Research Company</h3>
                        <div class="space-y-2">
                            <a href="https://www.linkedin.com/search/results/companies/?keywords={{ urlencode($job->company_name) }}" target="_blank" class="flex items-center justify-between p-2.5 rounded-md border border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50/50 transition-all active:scale-97 group">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded bg-[#0077b5]/10 flex items-center justify-center text-[#0077b5]">
                                        <i class="ph-bold ph-linkedin-logo text-sm"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-zinc-700">LinkedIn Search</span>
                                </div>
                                <i class="ph-bold ph-arrow-up-right text-zinc-450 group-hover:text-zinc-700 text-xs transition-colors"></i>
                            </a>

                            <a href="https://www.glassdoor.com/Search/results.htm?keyword={{ urlencode($job->company_name) }}" target="_blank" class="flex items-center justify-between p-2.5 rounded-md border border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50/50 transition-all active:scale-97 group">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded bg-[#0CAA41]/10 flex items-center justify-center text-[#0CAA41]">
                                        <i class="ph-bold ph-star text-sm"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-zinc-700">Glassdoor Reviews</span>
                                </div>
                                <i class="ph-bold ph-arrow-up-right text-zinc-450 group-hover:text-zinc-700 text-xs transition-colors"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Audit Trail / Timeline --}}
                    <div class="bg-white border border-zinc-200/60 p-5 rounded-lg shadow-3xs space-y-3.5">
                        <h3 class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Application Timeline</h3>
                        <div class="space-y-3.5 relative before:absolute before:left-[5px] before:top-1.5 before:bottom-1.5 before:w-px before:bg-zinc-150">
                            <div class="relative pl-5">
                                <div class="absolute left-0 top-1 w-2.5 h-2.5 rounded-full bg-primary-650 border-2 border-white shadow-3xs"></div>
                                <p class="text-[10px] font-bold text-zinc-700 uppercase tracking-wider">Applied</p>
                                <p class="text-[9px] font-semibold text-zinc-400 mt-0.5">{{ $job->application_date->format('d M Y') }}</p>
                            </div>
                            <div class="relative pl-5">
                                <div class="absolute left-0 top-1 w-2.5 h-2.5 rounded-full bg-zinc-300 border-2 border-white shadow-3xs"></div>
                                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Last Updated</p>
                                <p class="text-[9px] font-semibold text-zinc-400 mt-0.5">{{ $job->updated_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
