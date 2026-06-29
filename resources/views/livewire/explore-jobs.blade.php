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
    </div>

    <!-- Active Job Postings Grid -->
    @if ($postings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach ($postings as $job)
                <div class="bg-white rounded-xl border border-zinc-200 shadow-3xs p-4 flex flex-col justify-between hover:border-zinc-350 transition-all duration-150 h-[190px]">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="min-w-0">
                                <h3 class="text-xs font-bold text-zinc-900 truncate leading-tight tracking-tight">{{ $job->title }}</h3>
                                <span class="text-[10px] font-medium text-zinc-500 block truncate">{{ $job->company_name }}</span>
                            </div>
                            
                            <!-- Platform Badges -->
                            @if (str_contains($job->scraperSource->target_domain, 'linkedin'))
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase font-mono">
                                    LinkedIn
                                </span>
                            @elseif (str_contains($job->scraperSource->target_domain, 'jobstreet'))
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-red-50 text-red-700 border border-red-100 uppercase font-mono">
                                    JobStreet
                                </span>
                            @else
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase font-mono">
                                    Kalibrr
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
                            @if (session()->has('success_' . $job->id))
                                <span class="text-[9px] text-emerald-600 font-semibold block animate-pulse">Laporan Diterima!</span>
                            @elseif (session()->has('info_' . $job->id))
                                <span class="text-[9px] text-zinc-500 block font-semibold">{{ session('info_' . $job->id) }}</span>
                            @else
                                <button type="button" 
                                        wire:click="reportExpired({{ $job->id }})" 
                                        class="text-[9px] text-zinc-400 hover:text-red-600 transition-colors flex items-center gap-1">
                                    <i class="ph ph-warning-circle text-xs"></i>
                                    Laporkan Ditutup
                                </button>
                            @endif
                        </div>

                        <!-- Right Action: Apply Link -->
                        <a href="{{ $job->raw_url }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-1 text-[10px] font-bold text-zinc-950 hover:text-zinc-700 transition-colors">
                            Lamar Sekarang
                            <i class="ph-bold ph-arrow-up-right text-xs"></i>
                        </a>
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
