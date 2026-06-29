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
    <div class="relative bg-zinc-50/50 border border-zinc-200/80 rounded-2xl p-8 md:p-10 text-center shadow-3xs overflow-hidden mb-6 select-none">
        <!-- Radial Gradient background -->
        <div class="absolute inset-0 bg-gradient-to-b from-primary-50/20 via-transparent to-transparent pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl mx-auto space-y-4">
            <h2 class="text-xl md:text-2xl font-black text-zinc-800 tracking-tight leading-tight">
                Temukan Karir <span class="text-primary-650 block sm:inline">Impian Anda Hari Ini</span>
            </h2>
            <p class="text-[11px] md:text-xs text-zinc-400 max-w-lg mx-auto leading-relaxed">
                Jelajahi ribuan peluang magang & lowongan terverifikasi anti-ghosting dari berbagai perusahaan ternama di seluruh Indonesia.
            </p>

            <!-- Pill Search Bar (Unified Row) -->
            <div class="bg-white border border-zinc-200 rounded-xl md:rounded-full p-1.5 shadow-2xs flex flex-col md:flex-row items-stretch md:items-center gap-2 md:gap-0 mt-4">
                <!-- Search input -->
                <div class="flex-1 relative flex items-center pl-3">
                    <i class="ph ph-magnifying-glass text-zinc-400 text-xs absolute left-3"></i>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Posisi, skill, atau perusahaan..." 
                           class="w-full text-xs bg-transparent border-0 focus:ring-0 text-zinc-900 pl-6 pr-2 py-1.5 font-semibold placeholder-zinc-400 focus:outline-hidden">
                </div>

                <div class="hidden md:block w-[1px] h-5 bg-zinc-200"></div>

                <!-- Dropdown Provinsi -->
                <div class="relative flex items-center px-3">
                    <i class="ph ph-map-pin text-zinc-400 text-xs mr-1"></i>
                    <select wire:model.live="selectedProvince" class="bg-transparent border-0 focus:ring-0 text-[11px] py-1.5 pr-8 pl-1 font-bold text-zinc-700 cursor-pointer min-w-[125px] outline-none">
                        <option value="">Provinsi</option>
                        @foreach($provincesList as $provItem)
                            <option value="{{ $provItem }}">{{ $provItem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="hidden md:block w-[1px] h-5 bg-zinc-200"></div>

                <!-- Dropdown Kota -->
                <div class="relative flex items-center px-3">
                    <i class="ph ph-map-pin-line text-zinc-400 text-xs mr-1"></i>
                    <select wire:model.live="selectedLocation" class="bg-transparent border-0 focus:ring-0 text-[11px] py-1.5 pr-8 pl-1 font-bold text-zinc-700 cursor-pointer min-w-[125px] outline-none" {{ empty($selectedProvince) ? 'disabled' : '' }}>
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

                <div class="hidden md:block w-[1px] h-5 bg-zinc-200"></div>

                <!-- Dropdown Tipe Kerja -->
                <div class="relative flex items-center px-3">
                    <i class="ph ph-briefcase text-zinc-400 text-xs mr-1"></i>
                    <select wire:model.live="selectedWorkType" class="bg-transparent border-0 focus:ring-0 text-[11px] py-1.5 pr-8 pl-1 font-bold text-zinc-700 cursor-pointer min-w-[110px] outline-none">
                        <option value="">Tipe Kerja</option>
                        <option value="Onsite">Onsite</option>
                        <option value="Remote">Remote</option>
                        <option value="Hybrid">Hybrid</option>
                    </select>
                </div>

                <!-- Action Cari Button -->
                <button type="button" 
                        class="md:ml-2 px-6 h-[34px] bg-primary-650 hover:bg-primary-700 text-white text-[11px] font-black rounded-lg md:rounded-full uppercase tracking-wider transition-all duration-150 active:scale-97 flex items-center justify-center gap-1 shrink-0 focus:outline-hidden">
                    Cari
                </button>
            </div>

            <!-- Total Posisi, Refresh & Reset Row -->
            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-[9.5px] font-mono font-bold text-zinc-400 uppercase tracking-wider pt-2">
                <div class="flex items-center gap-1 text-zinc-650 bg-zinc-100 border border-zinc-200 px-2.5 py-0.5 rounded-full shadow-3xs">
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
                        class="flex items-center gap-1 hover:text-rose-650 transition-colors focus:outline-hidden cursor-pointer">
                    <i class="ph ph-trash text-xs"></i>
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Secondary Filters Box (Platform, Sektor & Jurusan) -->
    <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-3xs mb-6 select-none">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
            <!-- Platform Pills -->
            <div class="lg:col-span-6 flex gap-2">
                <button type="button" 
                        wire:click="$set('selectedPlatform', '')" 
                        class="flex-1 flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg text-[11px] font-bold border transition-all duration-150 shadow-3xs focus:outline-hidden {{ $selectedPlatform === '' ? 'bg-zinc-950 border-zinc-950 text-white' : 'bg-white border-zinc-200 text-zinc-650 hover:bg-zinc-50' }}">
                    <i class="ph ph-globe text-xs"></i>
                    <span>Semua</span>
                </button>
                <button type="button" 
                        wire:click="$set('selectedPlatform', 'linkedin.com')" 
                        class="flex-1 flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg text-[11px] font-bold border transition-all duration-150 shadow-3xs focus:outline-hidden {{ $selectedPlatform === 'linkedin.com' ? 'bg-[#0a66c2]/10 border-[#0a66c2]/30 text-[#0a66c2]' : 'bg-white border-zinc-200 text-zinc-650 hover:bg-zinc-50' }}">
                    <i class="ph-fill ph-linkedin-logo text-xs"></i>
                    <span>LinkedIn</span>
                </button>
                <button type="button" 
                        wire:click="$set('selectedPlatform', 'jobstreet.co.id')" 
                        class="flex-1 flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg text-[11px] font-bold border transition-all duration-150 shadow-3xs focus:outline-hidden {{ $selectedPlatform === 'jobstreet.co.id' ? 'bg-[#0d3b66]/10 border-[#0d3b66]/30 text-[#0d3b66]' : 'bg-white border-zinc-200 text-zinc-650 hover:bg-zinc-50' }}">
                    <i class="ph-bold ph-newspaper text-xs"></i>
                    <span>JobStreet</span>
                </button>
                <button type="button" 
                        wire:click="$set('selectedPlatform', 'kalibrr.com')" 
                        class="flex-1 flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg text-[11px] font-bold border transition-all duration-150 shadow-3xs focus:outline-hidden {{ $selectedPlatform === 'kalibrr.com' ? 'bg-[#00c0a3]/10 border-[#00c0a3]/30 text-[#00c0a3]' : 'bg-white border-zinc-200 text-zinc-650 hover:bg-zinc-50' }}">
                    <i class="ph-bold ph-briefcase text-xs"></i>
                    <span>Kalibrr</span>
                </button>
            </div>

            <!-- Sektor & Jurusan Dropdowns -->
            <div class="lg:col-span-6 grid grid-cols-2 gap-3">
                <div>
                    <select wire:model.live="selectedField" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-md px-2.5 py-1.5 text-zinc-700 outline-none transition-all focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white cursor-pointer font-semibold">
                        <option value="">Semua Sektor Bidang</option>
                        @foreach($fieldsList as $fieldItem)
                            <option value="{{ $fieldItem }}">{{ $fieldItem }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <select wire:model.live="selectedMajor" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-md px-2.5 py-1.5 text-zinc-700 outline-none transition-all focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white cursor-pointer font-semibold">
                        <option value="">Semua Jurusan</option>
                        @foreach($majorsList as $majorItem)
                            <option value="{{ $majorItem }}">{{ $majorItem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Job Postings Content Layout (Sidebar + Main Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-6">
        <!-- Sidebar: Location Tracker Explorer -->
        <div class="lg:col-span-1 flex flex-col gap-4">
            <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-3xs">
                <div class="flex items-center gap-2 mb-4 border-b border-zinc-150 pb-2">
                    <div class="w-7 h-7 bg-blue-50 border border-blue-150 text-blue-700 rounded-lg flex items-center justify-center shrink-0 shadow-3xs">
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
                                            : 'text-zinc-705 hover:bg-zinc-50 hover:text-zinc-950' }}">
                                <span class="truncate flex items-center gap-1.5">
                                    <i class="ph ph-caret-right text-[10px] transition-transform duration-200 {{ $isProvinceSelected ? 'rotate-90 text-blue-600' : 'text-zinc-400' }}"></i>
                                    {{ $provinceName }}
                                </span>
                                <span class="px-1.5 py-0.5 bg-zinc-50 border border-zinc-150 text-zinc-550 text-[9px] rounded-full font-mono font-bold shrink-0">
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
                                                        : 'text-zinc-550 hover:bg-zinc-50 hover:text-zinc-800' }}">
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
        <div class="lg:col-span-3">
            @if ($postings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach ($postings as $job)
                        @php
                            $firstLetter = strtoupper(substr($job->company_name, 0, 1));
                            $hash = md5($job->company_name);
                            $colors = [
                                ['bg-blue-50 text-blue-700 border-blue-150', 'bg-blue-500/10'],
                                ['bg-indigo-50 text-indigo-700 border-indigo-150', 'bg-indigo-500/10'],
                                ['bg-purple-50 text-purple-700 border-purple-150', 'bg-purple-500/10'],
                                ['bg-pink-50 text-pink-700 border-pink-150', 'bg-pink-500/10'],
                                ['bg-rose-50 text-rose-700 border-rose-150', 'bg-rose-500/10'],
                                ['bg-amber-50 text-amber-805 border-amber-200', 'bg-amber-500/10'],
                                ['bg-emerald-50 text-emerald-700 border-emerald-150', 'bg-emerald-500/10'],
                                ['bg-teal-50 text-teal-700 border-teal-150', 'bg-teal-500/10'],
                                ['bg-cyan-50 text-cyan-700 border-cyan-150', 'bg-cyan-500/10'],
                            ];
                            $colorIndex = hexdec(substr($hash, 0, 2)) % count($colors);
                            $colorClass = $colors[$colorIndex][0];
                            
                            $portalDomain = $job->scraperSource->target_domain;
                            $portalName = str_contains($portalDomain, 'linkedin') ? 'LinkedIn' : (str_contains($portalDomain, 'jobstreet') ? 'JobStreet' : 'Kalibrr');
                            $portalStyle = 'bg-blue-50/50 text-blue-700 border-blue-150';
                            $portalIcon = 'ph-fill ph-linkedin-logo';
                            if ($portalName === 'JobStreet') {
                                $portalStyle = 'bg-red-50/50 text-red-700 border-red-150';
                                $portalIcon = 'ph-bold ph-newspaper';
                            } elseif ($portalName === 'Kalibrr') {
                                $portalStyle = 'bg-emerald-50/50 text-emerald-700 border-emerald-150';
                                $portalIcon = 'ph-bold ph-briefcase';
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
                        <div class="bg-white rounded-xl border border-zinc-200/80 shadow-3xs p-4 flex flex-col justify-between hover:border-zinc-350 hover:shadow-2xs transition-all duration-150 min-h-[260px] h-auto group relative">
                            <div>
                                <!-- Header Card with Dynamic Company Logo and Portal Badge -->
                                <div class="flex gap-3 items-start mb-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm shrink-0 border uppercase shadow-3xs select-none {{ $colorClass }}">
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
                                                    {{ $matchPercent >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-250' : ($matchPercent >= 50 ? 'bg-amber-50 text-amber-700 border-amber-250' : 'bg-zinc-50 text-zinc-650 border-zinc-200') }}">
                                                    {{ $matchPercent }}% Match
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-1 select-none">
                                            <span class="text-[10px] font-medium text-zinc-550 truncate">{{ $job->company_name }}</span>
                                            <span class="text-zinc-300">&bull;</span>
                                            <span class="inline-flex items-center gap-0.5 px-1 py-0.2 rounded text-[8px] font-bold uppercase tracking-tight border {{ $portalStyle }} shrink-0">
                                                <i class="{{ $portalIcon }} text-[9px] shrink-0"></i>
                                                {{ $portalName }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Category & Location Tags -->
                                <div class="flex flex-wrap gap-1 mb-2.5 select-none">
                                    @if($job->category_field)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[8px] font-bold bg-primary-50 text-primary-700 border border-primary-100/60 uppercase tracking-tight">
                                            {{ $job->category_field }}
                                        </span>
                                    @endif
                                    @if($job->category_major)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[8px] font-bold bg-blue-50 text-blue-700 border border-blue-150 uppercase tracking-tight">
                                            {{ $job->category_major }}
                                        </span>
                                    @endif
                                    @if($job->work_type)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[8px] font-bold bg-amber-50 text-amber-705 border border-amber-200/65 uppercase tracking-tight">
                                            {{ $job->work_type }}
                                        </span>
                                    @endif
                                    @if($job->location)
                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md text-[8px] font-bold bg-zinc-50 text-zinc-600 border border-zinc-200/80 uppercase tracking-tight" title="{{ $job->location }}">
                                            <i class="ph ph-map-pin text-[9px] shrink-0 text-zinc-400"></i>
                                            {{ $job->location }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Tech Stack Tags -->
                                @if(!empty($jobStack))
                                    <div class="flex flex-wrap gap-1 mb-2.5 select-none">
                                        @foreach(array_slice($jobStack, 0, 4) as $tech)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[8px] font-bold bg-zinc-50 text-zinc-500 border border-zinc-150 uppercase tracking-tight">
                                                {{ $tech }}
                                            </span>
                                        @endforeach
                                        @if(count($jobStack) > 4)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[8px] font-bold bg-zinc-50 text-zinc-400 border border-zinc-150">
                                                +{{ count($jobStack) - 4 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Snippet description -->
                                <p class="text-[10px] text-zinc-450 leading-normal line-clamp-3 mb-3">
                                    {{ strip_tags($job->description) }}
                                </p>
                            </div>

                            <!-- Footer Action bar inside card -->
                            <div class="border-t border-zinc-100 pt-3 flex items-center justify-between gap-2 shrink-0">
                                <!-- Left Action: Report Expired Link -->
                                <div class="relative">
                                    @if (session()->has('report_success_' . $job->id))
                                        <span class="text-[9px] text-emerald-600 font-semibold block animate-pulse">Laporan Diterima!</span>
                                    @elseif (session()->has('report_info_' . $job->id))
                                        <span class="text-[9px] text-zinc-500 block font-semibold">{{ session('report_info_' . $job->id) }}</span>
                                    @else
                                        <button type="button" 
                                                wire:click="initiateReportExpired({{ $job->id }})" 
                                                class="text-[9px] text-zinc-400 hover:text-rose-600 transition-colors flex items-center gap-1 font-semibold uppercase tracking-wider focus:outline-hidden">
                                            <i class="ph ph-warning-circle text-xs"></i>
                                            Laporkan Ditutup
                                        </button>
                                    @endif
                                </div>

                                <!-- Right Action: Apply & Track Link -->
                                <div class="flex items-center gap-2">
                                    @if (session()->has('track_success_' . $job->id))
                                        <span class="inline-flex items-center gap-1 text-[9.5px] font-bold text-emerald-600">
                                            <i class="ph ph-check-circle text-xs"></i>
                                            Disimpan ke Tracker!
                                        </span>
                                    @elseif (session()->has('track_info_' . $job->id))
                                        <span class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider">
                                            Sudah Ada
                                        </span>
                                    @else
                                        <button type="button" 
                                                wire:click="initiateTrackJob({{ $job->id }})" 
                                                class="px-2.5 h-[26px] bg-primary-50 text-zinc-800 hover:bg-primary-100 border border-primary-200/60 text-[9.5px] font-bold rounded-md shadow-3xs transition-all active:scale-97 hover:shadow-2xs uppercase tracking-wider flex items-center justify-center focus:outline-hidden">
                                            Track
                                        </button>
                                    @endif
                                    <a href="{{ $job->raw_url }}" 
                                       target="_blank" 
                                       class="px-3 h-[26px] bg-zinc-950 text-white hover:bg-zinc-800 text-[9.5px] font-bold rounded-md shadow-3xs transition-all active:scale-97 hover:shadow-2xs uppercase tracking-wider flex items-center justify-center gap-0.5">
                                        Lamar
                                        <i class="ph-bold ph-arrow-up-right text-[10px]"></i>
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
                <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
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
                    <p class="text-[11px] text-zinc-550 mt-2 leading-relaxed">
                        Apakah Anda sudah melamar posisi <span class="font-bold text-zinc-800">{{ $confirmingJobTitle }}</span> di <span class="font-bold text-zinc-800">{{ $confirmingJobCompany }}</span> melalui portal <span class="font-bold text-zinc-800">{{ $confirmingJobPortal }}</span>?
                    </p>
                    <div class="mt-4 flex items-start gap-2 text-primary-700 bg-primary-50/30 p-3 rounded-md border border-primary-100/60 select-none">
                        <i class="ph ph-info text-base shrink-0"></i>
                        <p class="text-[10px] font-medium leading-relaxed">Menyimpan lowongan kerja ini akan mendaftarkannya sebagai lamaran aktif dalam pelacak karier Anda dengan status <span class="font-bold text-zinc-700">Applied</span>.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-t border-zinc-150/60 bg-zinc-50/50 flex justify-end gap-3 shrink-0">
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
                <div class="bg-white px-4 py-3 text-zinc-900 flex justify-between items-center border-b border-zinc-150/60 shrink-0">
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
                    <p class="text-[11px] text-zinc-550 mt-2 leading-relaxed">
                        Anda akan melaporkan posisi <span class="font-bold text-zinc-800">{{ $confirmingReportJobTitle }}</span> di <span class="font-bold text-zinc-800">{{ $confirmingReportJobCompany }}</span> sebagai expired/closed.
                    </p>
                    <div class="mt-4 flex items-start gap-2 text-rose-700 bg-rose-50/30 p-3 rounded-md border border-rose-100/60 select-none">
                        <i class="ph ph-warning-octagon text-base shrink-0"></i>
                        <p class="text-[10px] font-medium leading-relaxed">Melaporkan lowongan kerja yang sudah tidak aktif membantu menjaga kualitas informasi di TraKerja. Sistem akan secara otomatis mengarsipkan lowongan setelah menerima beberapa laporan serupa.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-t border-zinc-150/60 bg-zinc-50/50 flex justify-end gap-3 shrink-0">
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
