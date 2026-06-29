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

        <!-- Row 2: Advanced filters -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-3 pt-3 border-t border-zinc-100">
            <!-- Bidang Pekerjaan Dropdown -->
            <div>
                <label class="block text-[10px] font-mono font-medium text-zinc-400 uppercase tracking-wider mb-1">Bidang Kerja</label>
                <select wire:model.live="selectedField" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-2.5 py-1.5 text-zinc-800 focus:outline-hidden focus:border-zinc-950">
                    <option value="">Semua Bidang</option>
                    <option value="Backend Developer">Backend Developer</option>
                    <option value="Frontend Developer">Frontend Developer</option>
                    <option value="Fullstack Developer">Fullstack Developer</option>
                    <option value="Mobile Developer">Mobile Developer</option>
                    <option value="DevOps / Cloud">DevOps / Cloud</option>
                    <option value="QA / Testing">QA / Testing</option>
                    <option value="Data & AI">Data & AI</option>
                    <option value="Software Engineer">Software Engineer</option>
                </select>
            </div>

            <!-- Jurusan Dropdown -->
            <div>
                <label class="block text-[10px] font-mono font-medium text-zinc-400 uppercase tracking-wider mb-1">Jurusan Terkait</label>
                <select wire:model.live="selectedMajor" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-2.5 py-1.5 text-zinc-800 focus:outline-hidden focus:border-zinc-950">
                    <option value="">Semua Jurusan</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Matematika / Statistika">Matematika / Statistika</option>
                    <option value="Teknik Elektro">Teknik Elektro</option>
                    <option value="Semua Jurusan IT">Semua Jurusan IT</option>
                </select>
            </div>

            <!-- Tipe Kerja Dropdown -->
            <div>
                <label class="block text-[10px] font-mono font-medium text-zinc-400 uppercase tracking-wider mb-1">Tipe Kerja</label>
                <select wire:model.live="selectedWorkType" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded px-2.5 py-1.5 text-zinc-800 focus:outline-hidden focus:border-zinc-950">
                    <option value="">Semua Tipe</option>
                    <option value="Remote">Remote</option>
                    <option value="Hybrid">Hybrid</option>
                    <option value="Onsite">Onsite</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Active Job Postings Grid -->
    @if ($postings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach ($postings as $job)
                <div class="bg-white rounded-xl border border-zinc-200 shadow-3xs p-4 flex flex-col justify-between hover:border-zinc-350 transition-all duration-150 min-h-[220px] h-auto">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="min-w-0">
                                <h3 class="text-xs font-bold text-zinc-900 truncate leading-tight tracking-tight">{{ $job->title }}</h3>
                                <span class="text-[10px] font-medium text-zinc-500 block truncate">{{ $job->company_name }}</span>
                            </div>
                            
                            <!-- Platform Badges -->
                            @if (str_contains($job->scraperSource->target_domain, 'linkedin'))
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase font-mono shrink-0">
                                    LinkedIn
                                </span>
                            @elseif (str_contains($job->scraperSource->target_domain, 'jobstreet'))
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-red-50 text-red-700 border border-red-100 uppercase font-mono shrink-0">
                                    JobStreet
                                </span>
                            @else
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase font-mono shrink-0">
                                    Kalibrr
                                </span>
                            @endif
                        </div>

                        <!-- Category Tags -->
                        <div class="flex flex-wrap gap-1 mb-2.5">
                            @if($job->category_field)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-medium bg-zinc-50 text-zinc-600 border border-zinc-150">
                                    {{ $job->category_field }}
                                </span>
                            @endif
                            @if($job->category_major)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-medium bg-zinc-50 text-zinc-600 border border-zinc-150">
                                    {{ $job->category_major }}
                                </span>
                            @endif
                            @if($job->work_type)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-bold bg-amber-50 text-amber-800 border border-amber-250 uppercase font-mono">
                                    {{ $job->work_type }}
                                </span>
                            @endif
                        </div>

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
                                    Sudah Ditambahkan!
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
                                Lamar Sekarang
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
            <p class="text-[11px] text-zinc-500 max-w-sm mx-auto mb-4">Sistem scraping belum merekam data dari feed target. Pastikan cron scraper berjalan atau lakukan uji coba di panel admin.</p>
        </div>
    @endif
</div>
