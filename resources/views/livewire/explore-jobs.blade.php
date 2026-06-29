<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-1 text-[10px] font-mono text-zinc-400 uppercase tracking-widest mb-1">
                <span>User Portal</span>
                <span>/</span>
                <span class="text-zinc-600 font-semibold">Explore Jobs</span>
            </div>
            <h1 class="text-base font-bold text-zinc-950 tracking-tight">Cari Lowongan Kerja Aktif</h1>
            <p class="text-xs text-zinc-500">Hasil agregasi lowongan kerja terverifikasi anti-ghosting dari berbagai platform terpercaya.</p>
        </div>
    </div>

    <!-- Search and Filters bar -->
    <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-3xs mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari judul pekerjaan atau nama perusahaan..." 
                       class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-3 py-2 pl-9 text-zinc-900 focus:outline-hidden focus:border-zinc-950">
                <div class="absolute left-3 top-2.5 text-zinc-400">
                    <i class="ph ph-magnifying-glass text-sm"></i>
                </div>
            </div>
            
            <div class="md:col-span-2 flex gap-2">
                <!-- Platform Filters -->
                <button type="button" 
                        wire:click="$set('selectedPlatform', '')" 
                        class="flex-1 text-center py-1.5 px-3 rounded text-[11px] font-semibold border transition-all duration-150 {{ $selectedPlatform === '' ? 'bg-zinc-950 border-zinc-950 text-white' : 'bg-zinc-50 border-zinc-200 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-800' }}">
                    Semua
                </button>
                <button type="button" 
                        wire:click="$set('selectedPlatform', 'linkedin.com')" 
                        class="flex-1 text-center py-1.5 px-3 rounded text-[11px] font-semibold border transition-all duration-150 {{ $selectedPlatform === 'linkedin.com' ? 'bg-zinc-950 border-zinc-950 text-white' : 'bg-zinc-50 border-zinc-200 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-800' }}">
                    LinkedIn
                </button>
                <button type="button" 
                        wire:click="$set('selectedPlatform', 'jobstreet.co.id')" 
                        class="flex-1 text-center py-1.5 px-3 rounded text-[11px] font-semibold border transition-all duration-150 {{ $selectedPlatform === 'jobstreet.co.id' ? 'bg-zinc-950 border-zinc-950 text-white' : 'bg-zinc-50 border-zinc-200 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-800' }}">
                    JobStreet
                </button>
                <button type="button" 
                        wire:click="$set('selectedPlatform', 'kalibrr.com')" 
                        class="flex-1 text-center py-1.5 px-3 rounded text-[11px] font-semibold border transition-all duration-150 {{ $selectedPlatform === 'kalibrr.com' ? 'bg-zinc-950 border-zinc-950 text-white' : 'bg-zinc-50 border-zinc-200 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-800' }}">
                    Kalibrr
                </button>
            </div>
        </div>

        <!-- Row 2: Advanced filters (Forced single row on all viewports, labels hidden on mobile) -->
        <div class="grid grid-cols-5 gap-2 mt-3 pt-3 border-t border-zinc-100">
            <div>
                <label class="hidden sm:block text-[10px] font-mono font-medium text-zinc-400 uppercase tracking-wider mb-1">Bidang Kerja</label>
                <select wire:model.live="selectedField" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-2.5 py-1.5 text-zinc-800 focus:outline-hidden focus:border-zinc-950">
                    <option value="">Semua Bidang</option>
                    @foreach($fieldsList as $fieldItem)
                        <option value="{{ $fieldItem }}">{{ $fieldItem }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jurusan Dropdown -->
            <div>
                <label class="hidden sm:block text-[10px] font-mono font-medium text-zinc-400 uppercase tracking-wider mb-1">Jurusan Terkait</label>
                <select wire:model.live="selectedMajor" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-2.5 py-1.5 text-zinc-800 focus:outline-hidden focus:border-zinc-950">
                    <option value="">Semua Jurusan</option>
                    @foreach($majorsList as $majorItem)
                        <option value="{{ $majorItem }}">{{ $majorItem }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tipe Kerja Dropdown -->
            <div>
                <label class="hidden sm:block text-[10px] font-mono font-medium text-zinc-400 uppercase tracking-wider mb-1">Tipe Kerja</label>
                <select wire:model.live="selectedWorkType" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-2.5 py-1.5 text-zinc-800 focus:outline-hidden focus:border-zinc-950">
                    <option value="">Semua Tipe</option>
                    <option value="Remote">Remote</option>
                    <option value="Hybrid">Hybrid</option>
                    <option value="Onsite">Onsite</option>
                </select>
            </div>

            <!-- Provinsi Dropdown -->
            <div>
                <label class="hidden sm:block text-[10px] font-mono font-medium text-zinc-400 uppercase tracking-wider mb-1">Provinsi</label>
                <select wire:model.live="selectedProvince" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-2.5 py-1.5 text-zinc-800 focus:outline-hidden focus:border-zinc-950">
                    <option value="">Semua Provinsi</option>
                    @foreach($provincesList as $provItem)
                        <option value="{{ $provItem }}">{{ $provItem }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kota/Kabupaten Dropdown -->
            <div>
                <label class="hidden sm:block text-[10px] font-mono font-medium text-zinc-400 uppercase tracking-wider mb-1">Kota / Kabupaten</label>
                <select wire:model.live="selectedLocation" 
                        class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-2.5 py-1.5 text-zinc-800 focus:outline-hidden focus:border-zinc-950
                            {{ empty($selectedProvince) ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}" 
                        {{ empty($selectedProvince) ? 'disabled' : '' }}>
                    <option value="">
                        {{ empty($selectedProvince) ? 'Pilih Provinsi Dulu' : 'Semua Kota/Kab' }}
                    </option>
                    @if(!empty($selectedProvince))
                        @foreach($locationsList as $locItem)
                            <option value="{{ $locItem }}">{{ $locItem }}</option>
                        @endforeach
                    @endif
                </select>
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
                                class="w-5 h-5 flex items-center justify-center rounded-md hover:bg-blue-100 text-blue-500 hover:text-blue-800 transition-colors shrink-0">
                            <i class="ph ph-x text-xs"></i>
                        </button>
                    </div>
                @endif

                <!-- Provinces & Cities Accordion -->
                <div class="space-y-3 max-h-[500px] overflow-y-auto custom-scrollbar pr-1">
                    @php
                        $hasLocations = false;
                    @endphp
                    @foreach($locationStats as $provinceName => $provinceData)
                        @php
                            $hasLocations = true;
                            $isProvinceSelected = $selectedProvince === $provinceName;
                        @endphp
                        <div class="space-y-1">
                            <!-- Province Header Button -->
                            <button type="button" 
                                    wire:click="$set('selectedProvince', '{{ $provinceName }}'); $set('selectedLocation', '')" 
                                    class="w-full flex items-center justify-between text-[11px] font-bold py-1 text-left rounded transition-colors
                                        {{ $isProvinceSelected 
                                            ? 'text-blue-700 bg-blue-55/10 px-1 border-b border-blue-200' 
                                            : 'text-zinc-805 hover:text-zinc-950 border-b border-zinc-100' }}">
                                <span class="truncate">{{ $provinceName }}</span>
                                <span class="px-1.5 py-0.5 bg-zinc-100 border border-zinc-200 text-zinc-500 text-[9px] rounded font-mono font-bold shrink-0">
                                    {{ $provinceData['count'] }}
                                </span>
                            </button>
                            
                            <!-- Cities under Province -->
                            <div class="pl-2 border-l border-zinc-150 space-y-0.5">
                                @foreach($provinceData['cities'] as $cityInfo)
                                    @php
                                        $isCitySelected = $selectedLocation === $cityInfo['name'];
                                    @endphp
                                    <button type="button" 
                                            wire:click="$set('selectedProvince', '{{ $provinceName }}'); $set('selectedLocation', '{{ $cityInfo['name'] }}')" 
                                            class="w-full text-left flex items-center justify-between text-[10px] py-1 px-1.5 rounded transition-all duration-150 
                                                {{ $isCitySelected 
                                                    ? 'bg-blue-50 text-blue-800 font-bold border border-blue-200' 
                                                    : 'text-zinc-550 hover:bg-zinc-50 hover:text-zinc-850' }}">
                                        <span class="truncate">{{ $cityInfo['name'] }}</span>
                                        <span class="text-[9px] font-mono font-semibold text-zinc-400">
                                            {{ $cityInfo['count'] }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
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
                                ['bg-amber-50 text-amber-800 border-amber-200', 'bg-amber-500/10'],
                                ['bg-emerald-50 text-emerald-700 border-emerald-150', 'bg-emerald-500/10'],
                                ['bg-teal-50 text-teal-700 border-teal-150', 'bg-teal-500/10'],
                                ['bg-cyan-50 text-cyan-700 border-cyan-150', 'bg-cyan-500/10'],
                            ];
                            $colorIndex = hexdec(substr($hash, 0, 2)) % count($colors);
                            $colorClass = $colors[$colorIndex][0];
                            $portalDomain = $job->scraperSource->target_domain;

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
                        <div class="bg-white rounded-xl border border-zinc-200 shadow-3xs p-4 flex flex-col justify-between hover:border-zinc-350 hover:shadow-2xs transition-all duration-150 min-h-[250px] h-auto">
                            <div>
                                <!-- Header Card with Dynamic Company Logo and Portal Badge -->
                                <div class="flex gap-3 items-start mb-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm shrink-0 border uppercase {{ $colorClass }}">
                                        {{ $firstLetter }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-1">
                                            <h3 class="text-xs font-bold text-zinc-900 truncate leading-tight tracking-tight hover:text-zinc-800 transition-colors" title="{{ $job->title }}">
                                                {{ $job->title }}
                                            </h3>
                                            
                                            <!-- Portal Favicon & Match Badge -->
                                            <div class="flex items-center gap-1.5 shrink-0">
                                                @if(auth()->check() && $matchPercent !== null)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold tracking-tight border uppercase font-mono
                                                        {{ $matchPercent >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-250 animate-pulse' : ($matchPercent >= 50 ? 'bg-amber-50 text-amber-700 border-amber-250' : 'bg-zinc-50 text-zinc-650 border-zinc-200') }}">
                                                        {{ $matchPercent }}% Match
                                                    </span>
                                                @endif

                                                <div class="w-5.5 h-5.5 rounded-full bg-zinc-50 border border-zinc-200/80 flex items-center justify-center shrink-0 shadow-3xs p-0.5" title="{{ $job->scraperSource->name }}">
                                                    <img src="https://www.google.com/s2/favicons?domain={{ $portalDomain }}&sz=64" class="w-3.5 h-3.5 object-contain rounded-xs" alt="{{ $job->scraperSource->name }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-medium text-zinc-500 block truncate mt-0.5">{{ $job->company_name }}</span>
                                    </div>
                                </div>

                                <!-- Category & Location Tags -->
                                <div class="flex flex-wrap gap-1 mb-2.5">
                                    @if($job->category_field)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-medium bg-zinc-50 text-zinc-650 border border-zinc-150">
                                            {{ $job->category_field }}
                                        </span>
                                    @endif
                                    @if($job->category_major)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-medium bg-zinc-50 text-zinc-650 border border-zinc-150">
                                            {{ $job->category_major }}
                                        </span>
                                    @endif
                                    @if($job->work_type)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-bold bg-amber-50 text-amber-800 border border-amber-250 uppercase font-mono">
                                            {{ $job->work_type }}
                                        </span>
                                    @endif
                                    @if($job->location)
                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-sm text-[8px] font-bold bg-blue-50 text-blue-800 border border-blue-200 uppercase font-mono" title="{{ $job->location }}">
                                            <i class="ph ph-map-pin text-[9px] shrink-0"></i>
                                            {{ $job->location }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Tech Stack Tags -->
                                @if(!empty($jobStack))
                                    <div class="flex flex-wrap gap-1 mb-2.5">
                                        @foreach(array_slice($jobStack, 0, 4) as $tech)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-semibold bg-zinc-50 text-zinc-600 border border-zinc-200">
                                                {{ $tech }}
                                            </span>
                                        @endforeach
                                        @if(count($jobStack) > 4)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-semibold bg-zinc-50 text-zinc-400 border border-zinc-150">
                                                +{{ count($jobStack) - 4 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Snippet description -->
                                <p class="text-[10px] text-zinc-500 leading-normal line-clamp-3 mb-3">
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
                                                wire:click="reportExpired({{ $job->id }})" 
                                                class="text-[9px] text-zinc-400 hover:text-red-600 transition-colors flex items-center gap-1">
                                            <i class="ph ph-warning-circle text-xs"></i>
                                            Laporkan Ditutup
                                        </button>
                                    @endif
                                </div>

                                <!-- Right Action: Apply & Track Link -->
                                <div class="flex items-center gap-3">
                                    @if (session()->has('track_success_' . $job->id))
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600">
                                            <i class="ph ph-check-circle text-xs"></i>
                                            Disimpan ke Tracker!
                                        </span>
                                    @elseif (session()->has('track_info_' . $job->id))
                                        <span class="text-[10px] font-medium text-zinc-400">
                                            Sudah Ada
                                        </span>
                                    @else
                                        <button type="button" 
                                                wire:click="trackJob({{ $job->id }})" 
                                                class="inline-flex items-center gap-1 text-[10px] font-bold text-zinc-500 hover:text-zinc-950 transition-colors">
                                            <i class="ph ph-plus-circle text-xs"></i>
                                            Simpan Tracker
                                        </button>
                                    @endif
                                    <a href="{{ $job->raw_url }}" 
                                       target="_blank" 
                                       class="inline-flex items-center gap-1 text-[10px] font-bold text-zinc-950 hover:text-zinc-700 transition-colors">
                                        Lamar
                                        <i class="ph-bold ph-arrow-up-right text-xs"></i>
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
                <div class="bg-white border border-zinc-200 rounded-xl p-16 text-center shadow-3xs">
                    <i class="ph ph-briefcase-metal text-zinc-300 text-5xl mb-3 block mx-auto"></i>
                    <h3 class="text-xs font-bold text-zinc-800 mb-1">Belum Ada Lowongan Kerja Aktif</h3>
                    <p class="text-[11px] text-zinc-500 max-w-sm mx-auto mb-4">Sistem scraping belum merekam data dari feed target dengan kriteria filter terpilih.</p>
                </div>
            @endif
        </div>
    </div>
</div>
