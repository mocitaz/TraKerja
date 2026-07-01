<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Premium Notion-Inspired Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-6 select-none">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                <i class="ph ph-compass text-base"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-sm font-bold text-zinc-800 tracking-tight">Explore Jobs</h1>
                    <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60 leading-none">Scraper Feed</span>
                </div>
                <p class="text-[11px] text-zinc-400 mt-0.5">Hasil agregasi lowongan kerja terverifikasi anti-ghosting dari berbagai platform terpercaya.</p>
            </div>
        </div>
    </div>

    <!-- Premium Hero Search Card -->
    <div class="relative bg-white border border-zinc-200/80 rounded-2xl p-8 md:p-12 text-center shadow-3xs overflow-hidden mb-6 select-none">
        <!-- Background Image with low opacity and grayscale tone -->
        <div class="absolute inset-0 bg-cover bg-center grayscale opacity-25 pointer-events-none" style="background-image: url('{{ asset('images/ATL, Geogia 🙇🏽.jpeg') }}');"></div>
        <!-- Soft gradient overlay to blend -->
        <div class="absolute inset-0 bg-gradient-to-b from-white/5 via-white/20 to-white/70 pointer-events-none"></div>

        <div class="relative z-10 max-w-5xl mx-auto space-y-4">
            <h2 class="text-xl md:text-2xl font-black text-zinc-900 tracking-tight leading-tight">
                Temukan Karir Impian Anda Hari Ini
            </h2>
            <p class="text-[11px] md:text-xs text-zinc-500 max-w-xl mx-auto leading-relaxed">
                Jelajahi ribuan lowongan kerja terverifikasi anti-ghosting dari berbagai perusahaan ternama di seluruh Indonesia secara real-time.
                        <!-- Pill Search Bar (Unified Row) -->
            <div class="bg-white border border-zinc-200 rounded-2xl md:rounded-full p-2 md:p-1.5 shadow-2xs flex flex-col md:flex-row items-stretch md:items-center gap-2.5 md:gap-0 mt-6 max-w-5xl mx-auto">
                <!-- Search input -->
                <div class="flex-grow min-w-[150px] relative flex items-center pl-3">
                    <i class="ph ph-magnifying-glass text-zinc-400 text-xs absolute left-3"></i>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Posisi, skill, atau perusahaan..." 
                           class="w-full text-xs bg-transparent border-0 focus:ring-0 text-zinc-900 pl-6 pr-2 py-1.5 font-semibold placeholder-zinc-400 focus:outline-hidden">
                </div>

                <div class="hidden md:block w-[1px] h-5 bg-zinc-200 shrink-0"></div>

                <!-- Dropdown Sektor -->
                <div class="relative flex items-center px-3 md:px-2 py-1.5 md:py-0 border border-zinc-100 md:border-0 rounded-lg bg-zinc-50/50 md:bg-transparent">
                    <i class="ph ph-circles-four text-zinc-400 text-xs mr-2 md:mr-1 shrink-0"></i>
                    <select wire:model.live="selectedField" class="bg-transparent border-0 focus:ring-0 text-[11px] py-1 md:py-1.5 pr-8 pl-1 font-bold text-zinc-700 cursor-pointer w-full md:w-[120px] truncate outline-none select-none appearance-none">
                        <option value="">Semua Sektor</option>
                        @foreach($fieldsList as $fieldItem)
                            <option value="{{ $fieldItem }}">{{ $fieldItem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="hidden md:block w-[1px] h-5 bg-zinc-200 shrink-0"></div>

                <!-- Dropdown Jurusan -->
                <div class="relative flex items-center px-3 md:px-2 py-1.5 md:py-0 border border-zinc-100 md:border-0 rounded-lg bg-zinc-50/50 md:bg-transparent">
                    <i class="ph ph-graduation-cap text-zinc-400 text-xs mr-2 md:mr-1 shrink-0"></i>
                    <select wire:model.live="selectedMajor" class="bg-transparent border-0 focus:ring-0 text-[11px] py-1 md:py-1.5 pr-8 pl-1 font-bold text-zinc-700 cursor-pointer w-full md:w-[120px] truncate outline-none select-none appearance-none">
                        <option value="">Semua Jurusan</option>
                        @foreach($majorsList as $majorItem)
                            <option value="{{ $majorItem }}">{{ $majorItem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="hidden md:block w-[1px] h-5 bg-zinc-200 shrink-0"></div>

                <!-- Dropdown Provinsi -->
                <div class="relative flex items-center px-3 md:px-2 py-1.5 md:py-0 border border-zinc-100 md:border-0 rounded-lg bg-zinc-50/50 md:bg-transparent">
                    <i class="ph ph-map-pin text-zinc-400 text-xs mr-2 md:mr-1 shrink-0"></i>
                    <select wire:model.live="selectedProvince" class="bg-transparent border-0 focus:ring-0 text-[11px] py-1 md:py-1.5 pr-8 pl-1 font-bold text-zinc-700 cursor-pointer w-full md:w-[110px] truncate outline-none select-none appearance-none">
                        <option value="">Provinsi</option>
                        @foreach($provincesList as $provItem)
                            <option value="{{ $provItem }}">{{ $provItem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="hidden md:block w-[1px] h-5 bg-zinc-200 shrink-0"></div>

                <!-- Dropdown Kota -->
                <div class="relative flex items-center px-3 md:px-2 py-1.5 md:py-0 border border-zinc-100 md:border-0 rounded-lg bg-zinc-50/50 md:bg-transparent">
                    <i class="ph ph-map-pin-line text-zinc-400 text-xs mr-2 md:mr-1 shrink-0"></i>
                    <select wire:model.live="selectedLocation" class="bg-transparent border-0 focus:ring-0 text-[11px] py-1 md:py-1.5 pr-8 pl-1 font-bold text-zinc-700 cursor-pointer w-full md:w-[110px] truncate outline-none select-none appearance-none" {{ empty($selectedProvince) ? 'disabled' : '' }}>
                        @if(empty($selectedProvince))
                            <option value="">Pilih Provinsi</option>
                        @else
                            <option value="">Semua Kota</option>
                            @foreach($locationsList as $locItem)
                                <option value="{{ $locItem }}">{{ $locItem }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="hidden md:block w-[1px] h-5 bg-zinc-200 shrink-0"></div>

                <!-- Dropdown Tipe Kerja -->
                <div class="relative flex items-center px-3 md:px-2 py-1.5 md:py-0 border border-zinc-100 md:border-0 rounded-lg bg-zinc-50/50 md:bg-transparent">
                    <i class="ph ph-briefcase text-zinc-400 text-xs mr-2 md:mr-1 shrink-0"></i>
                    <select wire:model.live="selectedWorkType" class="bg-transparent border-0 focus:ring-0 text-[11px] py-1 md:py-1.5 pr-8 pl-1 font-bold text-zinc-700 cursor-pointer w-full md:w-[100px] truncate outline-none select-none appearance-none">
                        <option value="">Tipe Kerja</option>
                        <option value="Onsite">Onsite</option>
                        <option value="Remote">Remote</option>
                        <option value="Hybrid">Hybrid</option>
                    </select>
                </div>

                <!-- Action Cari Button -->
                <button type="button" 
                        class="md:ml-2 px-5 h-[34px] bg-primary-50 hover:bg-primary-100 text-zinc-800 border border-primary-200/60 text-[11px] font-black rounded-lg md:rounded-full uppercase tracking-wider transition-all duration-150 active:scale-97 flex items-center justify-center gap-1 shrink-0 focus:outline-hidden">
                    Cari
                </button>
            </div>

            <!-- Total Posisi, Refresh & Reset Row -->
            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-[9.5px] font-mono font-bold text-zinc-400 uppercase tracking-wider pt-2">
                <div class="flex items-center gap-1 text-zinc-600 bg-zinc-100 border border-zinc-200 px-2.5 py-0.5 rounded-full shadow-3xs">
                    <i class="ph ph-briefcase-metal text-[11px]"></i>
                    <span>Total Lowongan: {{ $postings->total() }}</span>
                </div>
                <span class="text-zinc-200 hidden sm:inline">&bull;</span>
                <button type="button" 
                        wire:click="$refresh" 
                        class="flex items-center gap-1 hover:text-zinc-700 transition-colors focus:outline-hidden cursor-pointer">
                    <i class="ph ph-arrows-clockwise text-xs"></i>
                    Refresh Feed
                </button>
                <span class="text-zinc-200 hidden sm:inline">&bull;</span>
                <button type="button" 
                        wire:click="resetFilters" 
                        class="flex items-center gap-1 hover:text-rose-600 transition-colors focus:outline-hidden cursor-pointer">
                    <i class="ph ph-trash text-xs"></i>
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Active Job Postings Content Layout (Sidebar + Main Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-6">
        <!-- Sidebar: Location Tracker Explorer -->
        <div class="lg:col-span-1 flex flex-col gap-4 order-2 lg:order-1">
            <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-3xs">
                <div class="flex items-center gap-2 mb-4 border-b border-zinc-200 pb-2">
                    <div class="w-7 h-7 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg flex items-center justify-center shrink-0 shadow-3xs">
                        <i class="ph ph-map-trifold text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-xs font-bold text-zinc-950 leading-tight">Location Tracker</h2>
                        <p class="text-[9px] text-zinc-400">Track & filter jobs by region</p>
                    </div>
                </div>

                <!-- Selected Location Badge -->
                @if ($selectedProvince || $selectedLocation)
                    <div class="mb-3 p-2 bg-blue-50/50 border border-blue-200 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <i class="ph-bold ph-map-pin text-blue-700 text-xs shrink-0"></i>
                            <span class="text-[10px] font-bold text-blue-800 truncate">
                                @if ($selectedLocation && $selectedProvince)
                                    {{ $selectedLocation }}, {{ $selectedProvince }}
                                @elseif ($selectedLocation)
                                    {{ $selectedLocation }}
                                @else
                                    {{ $selectedProvince }}
                                @endif
                            </span>
                        </div>
                        <button type="button" 
                                wire:click="resetLocationFilter" 
                                class="w-5 h-5 flex items-center justify-center rounded-md hover:bg-blue-100 text-blue-500 hover:text-blue-800 transition-colors shrink-0 focus:outline-hidden">
                            <i class="ph ph-x text-xs"></i>
                        </button>
                    </div>
                @endif

                <!-- Provinces & Cities Accordion -->
                <div class="space-y-1.5 max-h-[500px] overflow-y-auto custom-scrollbar pr-1">
                    @php
                        $hasLocations = false;
                    @endphp
                    @foreach($locationStats as $provinceName => $provinceData)
                        @php
                            $hasLocations = true;
                            $isProvinceSelected = $selectedProvince === $provinceName;
                        @endphp
                        <div class="rounded-lg overflow-hidden border {{ $isProvinceSelected ? 'border-blue-100 bg-blue-50/10' : 'border-transparent' }} transition-colors duration-200">
                            <!-- Province Header Button -->
                            <button type="button" 
                                    wire:click="selectLocation('{{ $provinceName }}', '')" 
                                    class="w-full flex items-center justify-between text-[11px] font-bold py-2 px-2.5 text-left rounded-lg transition-colors focus:outline-hidden
                                        {{ $isProvinceSelected 
                                            ? 'text-blue-800' 
                                            : 'text-zinc-700 hover:bg-zinc-50 hover:text-zinc-950' }}">
                                <span class="truncate flex items-center gap-1.5">
                                    <i class="ph ph-caret-right text-[10px] transition-transform duration-200 {{ $isProvinceSelected ? 'rotate-90 text-blue-600' : 'text-zinc-400' }}"></i>
                                    {{ $provinceName }}
                                </span>
                                <span class="px-1.5 py-0.5 bg-zinc-50 border border-zinc-200 text-zinc-500 text-[9px] rounded-full font-mono font-bold shrink-0">
                                    {{ $provinceData['count'] }}
                                </span>
                            </button>
                            
                            <!-- Cities under Province (Only expand if province is selected) -->
                            @if ($isProvinceSelected)
                                <div class="pl-5 pr-2.5 pb-2.5 space-y-1 animate-fadeIn">
                                    @foreach($provinceData['cities'] as $cityInfo)
                                        @php
                                            $isCitySelected = $selectedLocation === $cityInfo['name'];
                                        @endphp
                                        <button type="button" 
                                                wire:click="selectLocation('{{ $provinceName }}', '{{ $cityInfo['name'] }}')" 
                                                class="w-full text-left flex items-center justify-between text-[10px] py-1 px-2 rounded-md transition-all duration-150 focus:outline-hidden
                                                    {{ $isCitySelected 
                                                        ? 'bg-blue-50 text-blue-800 font-bold border border-blue-200 shadow-3xs' 
                                                        : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-800' }}">
                                            <span class="truncate flex items-center gap-1">
                                                <i class="ph ph-map-pin-line text-[9px] shrink-0 text-zinc-400"></i>
                                                {{ $cityInfo['name'] }}
                                            </span>
                                            <span class="text-[9px] font-mono font-semibold text-zinc-400 shrink-0">
                                                {{ $cityInfo['count'] }}
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach

                    @if (!$hasLocations)
                        <div class="text-center py-6 text-zinc-400 text-[10px]">
                            <i class="ph ph-map-pin-line text-lg mb-1 block"></i>
                            Belum ada lokasi terdeteksi.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content: Job Postings Grid -->
        <div class="lg:col-span-3 order-1 lg:order-2">
            @if ($postings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach ($postings as $job)
                        @php
                            $firstLetter = strtoupper(substr($job->company_name, 0, 1));
                            $hash = md5($job->company_name);
                            $colors = [
                                ['bg-blue-50 text-blue-700 border-blue-200', 'bg-blue-500/10'],
                                ['bg-indigo-50 text-indigo-700 border-indigo-200', 'bg-indigo-500/10'],
                                ['bg-purple-50 text-purple-700 border-purple-200', 'bg-purple-500/10'],
                                ['bg-pink-50 text-pink-700 border-pink-200', 'bg-pink-500/10'],
                                ['bg-rose-50 text-rose-700 border-rose-200', 'bg-rose-500/10'],
                                ['bg-amber-50 text-amber-800 border-amber-200', 'bg-amber-500/10'],
                                ['bg-emerald-50 text-emerald-700 border-emerald-200', 'bg-emerald-500/10'],
                                ['bg-teal-50 text-teal-700 border-teal-200', 'bg-teal-500/10'],
                                ['bg-cyan-50 text-cyan-700 border-cyan-200', 'bg-cyan-500/10'],
                            ];
                            $colorIndex = hexdec(substr($hash, 0, 2)) % count($colors);
                            $colorClass = $colors[$colorIndex][0];
                            
                            $portalDomain = $job->scraperSource->target_domain;
                            $portalName = str_contains($portalDomain, 'linkedin') ? 'LinkedIn' : (str_contains($portalDomain, 'jobstreet') ? 'JobStreet' : 'Kalibrr');
                            $portalIcon = 'ph-fill ph-linkedin-logo';
                            $portalIconColor = 'text-[#0a66c2]';
                            if ($portalName === 'JobStreet') {
                                $portalIcon = 'ph-fill ph-newspaper';
                                $portalIconColor = 'text-red-500';
                            } elseif ($portalName === 'Kalibrr') {
                                $portalIcon = 'ph-fill ph-briefcase';
                                $portalIconColor = 'text-emerald-500';
                            }
 
                            // Match user skills with tech stack
                            $userSkills = auth()->check() ? auth()->user()->skills->pluck('skill_name')->map(fn($s) => strtolower(trim($s)))->toArray() : [];
                            $jobStack = $job->tech_stack ?? [];
                            $matchedCount = 0;
                            $totalCount = count($jobStack);
                            $matchPercent = null;
                            if ($totalCount > 0 && !empty($userSkills)) {
                                foreach ($jobStack as $tech) {
                                    if (in_array(strtolower(trim($tech)), $userSkills)) {
                                        $matchedCount++;
                                    }
                                }
                                $matchPercent = round(($matchedCount / $totalCount) * 100);
                            }
                        @endphp
                        <div class="bg-white rounded-xl border border-zinc-200/80 shadow-3xs p-4 flex flex-col justify-between hover:border-zinc-350 hover:shadow-2xs transition-all duration-150 min-h-[220px] h-auto group relative">
                            <div>
                                <!-- Header Card with Dynamic Company Logo and Portal Badge -->
                                <div class="flex gap-2.5 items-start mb-3">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-xs shrink-0 border uppercase shadow-3xs select-none {{ $colorClass }}">
                                        {{ $firstLetter }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-1">
                                            <h3 class="text-xs font-bold text-zinc-900 truncate leading-tight tracking-tight hover:text-zinc-800 transition-colors" title="{{ $job->title }}">
                                                {{ $job->title }}
                                            </h3>
                                            
                                            <!-- Match Badge -->
                                            @if(auth()->check() && $matchPercent !== null)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold tracking-tight border uppercase font-mono shrink-0
                                                    {{ $matchPercent >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : ($matchPercent >= 50 ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-zinc-50 text-zinc-600 border-zinc-200') }}">
                                                    {{ $matchPercent }}% Match
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-1 select-none">
                                            <span class="text-[10px] font-semibold text-zinc-500 truncate">{{ $job->company_name }}</span>
                                        </div>
                                    </div>
                                </div>
 
                                <!-- Category & Location Tags -->
                                <div class="flex flex-wrap gap-1 mb-2 select-none">
                                    @if($job->category_field && strtolower($job->category_field) !== 'semua bidang')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8.5px] font-semibold bg-primary-50 text-primary-700 border border-primary-100/50">
                                            {{ Str::title(str_replace('Sektor ', '', $job->category_field)) }}
                                        </span>
                                    @endif
                                    @if($job->category_major && strtolower($job->category_major) !== 'semua jurusan')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8.5px] font-semibold bg-blue-50/70 text-blue-700 border border-blue-100/50">
                                            {{ Str::title($job->category_major) }}
                                        </span>
                                    @endif
                                    @if($job->work_type)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8.5px] font-bold bg-amber-50 text-amber-700 border border-amber-200/50">
                                            {{ Str::title($job->work_type) }}
                                        </span>
                                    @endif
                                    @if($job->location)
                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[8.5px] font-semibold bg-zinc-50 text-zinc-600 border border-zinc-200/80" title="{{ $job->location }}">
                                            <i class="ph ph-map-pin text-[9px] shrink-0 text-zinc-400"></i>
                                            {{ Str::title($job->location) }}
                                        </span>
                                    @endif
                                </div>
 
                                <!-- Tech Stack Tags -->
                                @if(!empty($jobStack))
                                    <div class="flex flex-wrap gap-1 mb-2 select-none">
                                        @foreach(array_slice($jobStack, 0, 3) as $tech)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-zinc-50 text-zinc-500 border border-zinc-200 uppercase tracking-tight">
                                                {{ $tech }}
                                            </span>
                                        @endforeach
                                        @if(count($jobStack) > 3)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-zinc-50 text-zinc-400 border border-zinc-200">
                                                +{{ count($jobStack) - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
 
                                <!-- Snippet description -->
                                <p class="text-[10px] text-zinc-400 leading-relaxed line-clamp-2 mb-3 mt-1.5 italic font-medium">
                                    {{ strip_tags($job->description) }}
                                </p>
                            </div>

                            <!-- Footer Action bar inside card -->
                            <div class="border-t border-zinc-100 pt-3 flex items-center justify-between gap-2 shrink-0">
                                <!-- Left Action: Platform Icon & Report Expired Link -->
                                <div class="flex items-center gap-2 select-none">
                                    <!-- Clear Platform Logo -->
                                    <i class="{{ $portalIcon }} {{ $portalIconColor }} text-xs shrink-0" title="{{ $portalName }}"></i>
                                    <span class="text-zinc-200 text-[10px] shrink-0">&bull;</span>
                                    
                                    <div class="relative">
                                        @if (session()->has('report_success_' . $job->id))
                                            <span class="text-[9px] text-emerald-600 font-semibold block animate-pulse">Laporan Diterima!</span>
                                        @elseif (session()->has('report_info_' . $job->id))
                                            <span class="text-[9px] text-zinc-500 block font-semibold">{{ session('report_info_' . $job->id) }}</span>
                                        @else
                                            <button type="button" 
                                                    wire:click="initiateReportExpired({{ $job->id }})" 
                                                    class="text-[9px] text-zinc-400 hover:text-rose-600 transition-colors flex items-center gap-1 font-bold uppercase tracking-wider focus:outline-hidden">
                                                <i class="ph ph-warning-circle text-[11px] shrink-0"></i>
                                                Laporkan
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right Action: Apply & Track Link -->
                                <div class="flex items-center gap-1.5">
                                    @if (session()->has('track_success_' . $job->id))
                                        <span class="w-7 h-7 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md flex items-center justify-center shadow-3xs" title="Disimpan ke Tracker!">
                                            <i class="ph ph-check-circle text-[13px]"></i>
                                        </span>
                                    @elseif (session()->has('track_info_' . $job->id))
                                        <span class="w-7 h-7 bg-zinc-50 text-zinc-400 border border-zinc-200 rounded-md flex items-center justify-center shadow-3xs" title="Sudah disimpan sebelumnya">
                                            <i class="ph ph-check text-[13px]"></i>
                                        </span>
                                    @else
                                        <button type="button" 
                                                wire:click="initiateTrackJob({{ $job->id }})" 
                                                title="Simpan ke Tracker"
                                                class="w-7 h-7 bg-primary-50 text-primary-750 hover:bg-primary-100 border border-primary-200/50 rounded-md shadow-3xs transition-all active:scale-97 flex items-center justify-center focus:outline-hidden">
                                            <i class="ph ph-folder-notch-plus text-[13px]"></i>
                                        </button>
                                    @endif
                                    <a href="{{ $job->raw_url }}" 
                                       target="_blank" 
                                       title="Lamar Lowongan (Buka Link)"
                                       class="w-7 h-7 bg-zinc-950 text-white hover:bg-zinc-800 rounded-md shadow-3xs transition-all active:scale-97 flex items-center justify-center">
                                        <i class="ph-bold ph-arrow-up-right text-[11px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $postings->links() }}
                </div>
            @else
                <!-- Empty State listings -->
                <div class="bg-white border border-zinc-200 rounded-xl p-16 text-center shadow-3xs select-none">
                    <i class="ph ph-briefcase-metal text-zinc-300 text-5xl mb-3 block mx-auto animate-pulse"></i>
                    <h3 class="text-xs font-bold text-zinc-800 mb-1">Belum Ada Lowongan Kerja Aktif</h3>
                    <p class="text-[11px] text-zinc-500 max-w-sm mx-auto mb-4">Sistem scraping belum merekam data dari feed target dengan kriteria filter terpilih.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Confirmation Modal: Save to Tracker -->
    @if($confirmingTrackJobId)
        <div class="fixed inset-0 z-[99999] bg-zinc-950/40 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200 transform transition-all animate-fadeIn">
                <!-- Modal Header: Clean White -->
                <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-200/60 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/60 flex items-center justify-center shadow-3xs">
                            <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-4 h-4 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Simpan Tracker</h3>
                                <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[8.5px] font-bold uppercase tracking-wider rounded border border-primary-100/60 leading-none">Confirm</span>
                            </div>
                            <p class="text-zinc-400 text-[9px] font-medium mt-0.5">Career Growth Tracking</p>
                        </div>
                    </div>
                    <button wire:click="cancelTrackJob" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 transition-all text-zinc-400 hover:text-zinc-800 focus:outline-hidden">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-5 bg-white overflow-y-auto custom-scrollbar flex-1 flex flex-col text-left">
                    <p class="text-xs text-zinc-700 leading-relaxed font-semibold">
                        Apakah Anda yakin sudah melamar lowongan kerja ini?
                    </p>
                    <p class="text-[11px] text-zinc-500 mt-2 leading-relaxed">
                        Apakah Anda sudah melamar posisi <span class="font-bold text-zinc-800">{{ $confirmingJobTitle }}</span> di <span class="font-bold text-zinc-800">{{ $confirmingJobCompany }}</span> melalui portal <span class="font-bold text-zinc-800">{{ $confirmingJobPortal }}</span>?
                    </p>
                    <div class="mt-4 flex items-start gap-2 text-primary-700 bg-primary-50/30 p-3 rounded-md border border-primary-100/60 select-none">
                        <i class="ph ph-info text-base shrink-0"></i>
                        <p class="text-[10px] font-medium leading-relaxed">Menyimpan lowongan kerja ini akan mendaftarkannya sebagai lamaran aktif dalam pelacak karier Anda dengan status <span class="font-bold text-zinc-700">Applied</span>.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-t border-zinc-200/60 bg-zinc-50/50 flex justify-end gap-3 shrink-0">
                    <a href="{{ $confirmingJobUrl }}" 
                       target="_blank" 
                       wire:click="cancelTrackJob"
                       class="h-[30px] flex items-center justify-center text-[10px] font-bold text-zinc-500 hover:text-zinc-700 uppercase tracking-wider transition-colors focus:outline-hidden">
                        Belum, Lamar Dulu
                    </a>
                    
                    <button type="button" 
                            wire:click="confirmTrackJob" 
                            class="px-4 h-[30px] bg-primary-50 text-zinc-800 hover:bg-primary-100 border border-primary-200/60 text-[10px] font-bold rounded-md shadow-3xs transition-all active:scale-97 hover:shadow-2xs uppercase tracking-wider flex items-center justify-center focus:outline-hidden">
                        Ya, Sudah Melamar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Confirmation Modal: Report Expired -->
    @if($confirmingReportJobId)
        <div class="fixed inset-0 z-[99999] bg-zinc-950/40 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200 transform transition-all animate-fadeIn">
                <!-- Modal Header: Clean White -->
                <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-200/60 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 border border-zinc-200/60 flex items-center justify-center shadow-3xs">
                            <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-4 h-4 object-contain" onerror="this.src='{{ asset('favicon.png') }}'">
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Laporkan Ditutup</h3>
                                <span class="px-1.5 py-0.5 bg-rose-50 text-rose-800 text-[8.5px] font-bold uppercase tracking-wider rounded border border-rose-100/60 leading-none">Report</span>
                            </div>
                            <p class="text-zinc-400 text-[9px] font-medium mt-0.5">Job Board Integrity</p>
                        </div>
                    </div>
                    <button wire:click="cancelReportExpired" class="w-7 h-7 flex items-center justify-center rounded hover:bg-zinc-50 transition-all text-zinc-400 hover:text-zinc-800 focus:outline-hidden">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-5 bg-white overflow-y-auto custom-scrollbar flex-1 flex flex-col text-left">
                    <p class="text-xs text-zinc-700 leading-relaxed font-semibold">
                        Apakah Anda yakin ingin melaporkan bahwa lowongan kerja ini sudah ditutup?
                    </p>
                    <p class="text-[11px] text-zinc-500 mt-2 leading-relaxed">
                        Anda akan melaporkan posisi <span class="font-bold text-zinc-800">{{ $confirmingReportJobTitle }}</span> di <span class="font-bold text-zinc-800">{{ $confirmingReportJobCompany }}</span> sebagai expired/closed.
                    </p>
                    <div class="mt-4 flex items-start gap-2 text-rose-700 bg-rose-50/30 p-3 rounded-md border border-rose-100/60 select-none">
                        <i class="ph ph-warning-octagon text-base shrink-0"></i>
                        <p class="text-[10px] font-medium leading-relaxed">Melaporkan lowongan kerja yang sudah tidak aktif membantu menjaga kualitas informasi di TraKerja. Sistem akan secara otomatis mengarsipkan lowongan setelah menerima beberapa laporan serupa.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-t border-zinc-200/60 bg-zinc-50/50 flex justify-end gap-3 shrink-0">
                    <button type="button" 
                            wire:click="cancelReportExpired"
                            class="h-[30px] flex items-center justify-center text-[10px] font-bold text-zinc-500 hover:text-zinc-700 uppercase tracking-wider transition-colors focus:outline-hidden">
                        Batal
                    </button>
                    
                    <button type="button" 
                            wire:click="confirmReportExpired" 
                            class="px-4 h-[30px] bg-rose-50 text-rose-800 hover:bg-rose-100 border border-rose-200/60 text-[10px] font-bold rounded-md shadow-3xs transition-all active:scale-97 hover:shadow-2xs uppercase tracking-wider flex items-center justify-center focus:outline-hidden">
                        Ya, Laporkan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
