<div class="relative">
    @if (session()->has('message'))
        <div class="mb-4 bg-emerald-50 border border-emerald-100 rounded-md p-3 flex items-center gap-2 animate-fadeIn">
            <i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i>
            <p class="text-[11px] font-bold text-emerald-800">{{ session('message') }}</p>
        </div>
    @endif

    {{-- Global Error Feedback --}}
    @if ($errors->any())
        <div class="mb-4 bg-rose-50 border border-rose-100 rounded-md p-3 flex items-start gap-2.5 animate-fadeIn">
            <i class="ph-fill ph-warning-circle text-rose-500 text-base shrink-0 mt-0.5"></i>
            <div>
                <p class="text-[10px] font-bold text-rose-800 uppercase tracking-wider">Action Required</p>
                <p class="text-[10px] font-medium text-rose-600 mt-0.5">Some fields are invalid. Please check the highlights below.</p>
            </div>
        </div>
    @endif

    <form wire:submit="save" class="space-y-4">
        {{-- Row 1: Company & Position --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Company *</label>
                <div class="relative group">
                    <i class="ph-bold ph-buildings absolute left-2.5 top-1/2 -translate-y-1/2 text-zinc-400 text-sm group-focus-within:text-zinc-700 transition-colors"></i>
                    <input wire:model.live="company_name" type="text" class="block w-full pl-8 pr-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 focus:ring-1 focus:ring-primary-500/20 focus:bg-white focus:border-primary-500 transition-all outline-none" placeholder="e.g. Google">
                </div>
                @error('company_name') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Position *</label>
                <div class="relative group">
                    <i class="ph-bold ph-identification-card absolute left-2.5 top-1/2 -translate-y-1/2 text-zinc-400 text-sm group-focus-within:text-zinc-700 transition-colors"></i>
                    <input wire:model.live="position" list="position-suggestions" type="text" class="block w-full pl-8 pr-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 focus:ring-1 focus:ring-primary-500/20 focus:bg-white focus:border-primary-500 transition-all outline-none" placeholder="e.g. Designer">
                    @if(!empty($previousPositions))
                    <datalist id="position-suggestions">
                        @foreach($previousPositions as $pos)
                            <option value="{{ $pos }}">
                        @endforeach
                    </datalist>
                    @endif
                </div>
                @error('position') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 2: Location --}}
        @if(!$isRemote && !$isInternational)
        <div class="animate-fadeIn">
            <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Location Context *</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <select wire:key="province-select" wire:model.live="selectedProvince" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    <option value="">Select Province</option>
                    @foreach($provinces as $province => $cities) <option value="{{ $province }}">{{ $province }}</option> @endforeach
                </select>
                <select wire:key="city-select-{{ $selectedProvince }}" wire:model.live="selectedCity" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none transition-all focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white {{ empty($selectedProvince) ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}" {{ empty($selectedProvince) ? 'disabled' : '' }}>
                    <option value="">Select City</option>
                    @if(!empty($selectedProvince) && isset($provinces[$selectedProvince]))
                        @foreach($provinces[$selectedProvince] as $city) <option value="{{ $city }}">{{ $city }}</option> @endforeach
                    @endif
                </select>
            </div>
            @error('selectedProvince') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
        </div>
        @endif

        {{-- Row 3: Work Mode (Segmented Style) --}}
        @if(empty($selectedProvince))
        <div class="grid grid-cols-2 gap-2 bg-zinc-50/50 p-1 border border-zinc-200/60 rounded-lg animate-fadeIn">
            <label class="flex items-center justify-center gap-2 py-1.5 px-3 cursor-pointer group transition-all rounded-md bg-white border border-zinc-200 hover:border-zinc-300 active:scale-97 hover:shadow-3xs">
                <input type="checkbox" wire:model.live="isRemote" class="sr-only peer">
                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-400 peer-checked:bg-primary-50 peer-checked:text-zinc-800 peer-checked:border-primary-200/60 transition-all">
                    <i class="ph-bold ph-house-line text-xs"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-zinc-500 peer-checked:text-zinc-800 transition-colors">Remote</span>
                </div>
            </label>
            <label class="flex items-center justify-center gap-2 py-1.5 px-3 cursor-pointer group transition-all rounded-md bg-white border border-zinc-200 hover:border-zinc-300 active:scale-97 hover:shadow-3xs">
                <input type="checkbox" wire:model.live="isInternational" class="sr-only peer">
                <div class="w-6 h-6 rounded bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-400 peer-checked:bg-primary-50 peer-checked:text-zinc-800 peer-checked:border-primary-200/60 transition-all">
                    <i class="ph-bold ph-airplane-tilt text-xs"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-zinc-500 peer-checked:text-zinc-800 transition-colors">International</span>
                </div>
            </label>
        </div>
        @endif

        {{-- International Location (Visible when International is checked) --}}
        @if($isInternational)
        <div class="animate-fadeIn pt-0.5">
            <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">International Location *</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <select wire:key="country-select" wire:model.live="selectedCountry" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    <option value="">Select Country</option>
                    @foreach($countries as $country => $cities) <option value="{{ $country }}">{{ $country }}</option> @endforeach
                </select>
                <select wire:key="int-city-select-{{ $selectedCountry }}" wire:model.live="selectedInternationalCity" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white {{ empty($selectedCountry) ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}" {{ empty($selectedCountry) ? 'disabled' : '' }}>
                    <option value="">Select City</option>
                    @if(!empty($selectedCountry) && isset($countries[$selectedCountry]))
                        @foreach($countries[$selectedCountry] as $city) <option value="{{ $city }}">{{ $city }}</option> @endforeach
                    @endif
                </select>
            </div>
            @error('selectedCountry') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
        </div>
        @endif

        {{-- Row 4: Platform & Career Level --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Platform *</label>
                <select wire:model.live="platform" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    <option value="">Select Platform</option>
                    @foreach($platformOptions as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                @if(empty($platform) && !empty($topPlatforms))
                <div class="mt-1.5 flex items-center gap-1.5 overflow-x-auto hide-scrollbar pb-0.5">
                    @foreach($topPlatforms as $tp)
                        <button type="button" wire:click="$set('platform', '{{ $tp }}')" class="px-2 py-1 bg-primary-50 hover:bg-primary-100 border border-primary-200/50 text-zinc-800 rounded text-[9.5px] font-bold transition-all whitespace-nowrap active:scale-97">
                            {{ $tp }}
                        </button>
                    @endforeach
                </div>
                @endif
                @error('platform') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Career Level</label>
                <select wire:model.live="career_level" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    <option value="">Select Level</option>
                    @foreach($careerLevelOptions as $cl) <option value="{{ $cl }}">{{ $cl }}</option> @endforeach
                </select>
                @error('career_level') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 5: Status & Stage --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">App Status *</label>
                <select wire:model.live="application_status" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    @foreach($applicationStatusOptions as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
                </select>
                @error('application_status') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Current Stage *</label>
                <select wire:model.live="recruitment_stage" class="block w-full py-0 pl-2.5 pr-8 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                    @foreach($recruitmentStageOptions as $stg) <option value="{{ $stg }}">{{ $stg }}</option> @endforeach
                </select>
                @error('recruitment_stage') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 6: App Link & Date --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Job Link (URL)</label>
                    <button type="button" onclick="fetchJobDetailsFromUrl()" id="scrape-btn" class="px-2 py-0.5 bg-primary-50 hover:bg-primary-100 text-zinc-800 border border-primary-200/60 rounded text-[9px] font-bold transition-all flex items-center gap-1 active:scale-97">
                        <i class="ph-bold ph-sparkle text-[10px]"></i>
                        <span>Auto-Fill Info</span>
                    </button>
                </div>
                <div class="relative group">
                    <i class="ph-bold ph-link absolute left-2.5 top-1/2 -translate-y-1/2 text-zinc-400 text-sm group-focus-within:text-zinc-700 transition-colors"></i>
                    <input id="job-url-input" wire:model.live="platform_link" type="url" class="block w-full pl-8 pr-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 focus:ring-1 focus:ring-primary-500/20 focus:bg-white focus:border-primary-500 transition-all outline-none" placeholder="https://...">
                </div>
                @error('platform_link') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Application Date *</label>
                <div class="relative">
                    <input wire:model.live="application_date" type="date" class="block w-full px-3 h-[32px] bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all cursor-pointer">
                </div>
                @error('application_date') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Interview Fields (visible when status = Interview or stage = HR/User Interview) --}}
        @if(in_array($application_status, ['Interview']) || in_array($recruitment_stage, ['HR - Interview', 'User - Interview']))
        <div class="bg-primary-50/20 border border-primary-100/50 rounded-lg p-3 space-y-2.5 animate-fadeIn">
            <div class="flex items-center gap-2 mb-0.5">
                <div class="w-5 h-5 bg-primary-100/60 rounded flex items-center justify-center">
                    <i class="ph-bold ph-chats-circle text-zinc-700 text-[10px]"></i>
                </div>
                <span class="text-[9.5px] font-bold text-zinc-800 uppercase tracking-wider">Interview Details</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div>
                    <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Interview Date & Time</label>
                    <input wire:model.live="interview_date" type="datetime-local"
                           class="block w-full px-3 h-[30px] bg-white border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    @error('interview_date') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Interview Type</label>
                    <select wire:model.live="interview_type"
                            class="block w-full py-0 pl-2.5 pr-8 h-[30px] bg-white border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                        <option value="">Select Type</option>
                        @foreach(['Phone', 'Video', 'In-person', 'Panel'] as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                    @error('interview_type') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-[9px] font-bold text-zinc-400 uppercase tracking-wider mb-1">
                    {{ in_array($interview_type, ['Phone','Video']) ? 'Meeting Link' : 'Location / Link' }}
                </label>
                <div class="relative">
                    <i class="ph-bold {{ in_array($interview_type, ['Phone','Video']) ? 'ph-video-camera' : 'ph-map-pin' }} absolute left-2.5 top-1/2 -translate-y-1/2 text-zinc-400 text-sm"></i>
                    <input wire:model.live="interview_location" type="text"
                           class="block w-full pl-8 pr-3 h-[30px] bg-white border border-zinc-200 rounded-md text-xs font-semibold text-zinc-700 outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                           placeholder="{{ in_array($interview_type, ['Phone','Video']) ? 'https://meet.google.com/...' : 'Office address or meeting link' }}">
                </div>
                @error('interview_location') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
            </div>
        </div>
        @endif

        {{-- Notes --}}
        <div>
            <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Brief Notes</label>
            <textarea wire:model.live="notes" rows="2" class="block w-full px-3 py-2 bg-zinc-50/40 border border-zinc-200 rounded-md text-xs font-medium text-zinc-700 outline-none resize-none transition-all focus:bg-white focus:border-primary-500" placeholder="Add specific reminders..."></textarea>
            @error('notes') <p class="text-rose-500 text-[9px] font-semibold mt-1 ml-0.5">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-zinc-150/60 mt-1">
            <button type="button" wire:click="resetForm" class="text-[10px] font-bold text-zinc-400 hover:text-rose-500 uppercase tracking-wider transition-colors">Reset</button>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeJobModal()" class="text-[10px] font-bold text-zinc-500 hover:text-zinc-700 uppercase tracking-wider transition-all">Cancel</button>
                <button type="submit" class="px-4 h-[30px] bg-primary-50 text-zinc-800 hover:bg-primary-100 border border-primary-200/60 text-[10px] font-bold rounded-md shadow-3xs transition-all active:scale-97 hover:shadow-2xs uppercase tracking-wider flex items-center gap-1.5 group focus:outline-none">
                    <span wire:loading.remove wire:target="save" class="flex items-center gap-1.5">
                        <i class="ph-bold ph-check text-xs"></i>
                        {{ $isEditing ? 'UPDATE' : 'SAVE' }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                        <i class="ph-bold ph-spinner animate-spin text-xs"></i>
                        PROCESSING...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
window.fetchJobDetailsFromUrl = window.fetchJobDetailsFromUrl || function() {
    const urlInput = document.getElementById('job-url-input');
    const url = urlInput ? urlInput.value.trim() : '';
    if (!url) {
        alert('Mohon masukkan URL lowongan kerja terlebih dahulu!');
        return;
    }
    const btn = document.getElementById('scrape-btn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="ph ph-spinner animate-spin text-[10px]"></i> <span>Fetching...</span>';
    }

    fetch('/jobs/scrape-url', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ url: url })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (data.company_name && window.Livewire) {
                @this.set('company_name', data.company_name);
            }
            if (data.job_title && window.Livewire) {
                @this.set('position', data.job_title);
            }
            if (data.location && window.Livewire) {
                @this.set('location', data.location);
                @this.call('parseLocation', data.location);
            }

            // Auto-detect platform from URL
            let detectPlatform = '';
            if (url.includes('linkedin.com')) {
                detectPlatform = 'LinkedIn';
            } else if (url.includes('jobstreet.com') || url.includes('seek.com')) {
                detectPlatform = 'JobStreet';
            } else if (url.includes('talentics.id')) {
                detectPlatform = 'Talentics';
            } else if (url.includes('dealls.com')) {
                detectPlatform = 'Dealls';
            } else if (url.includes('kalibrr.com')) {
                detectPlatform = 'Kalibrr';
            } else if (url.includes('glints.com')) {
                detectPlatform = 'Glints';
            }
            if (detectPlatform && window.Livewire) {
                @this.set('platform', detectPlatform);
            }

            if (btn) {
                btn.disabled = false;
                // Add green success classes
                btn.classList.remove('bg-primary-50', 'text-zinc-800', 'border-primary-200/60');
                btn.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-250');
                btn.innerHTML = '<i class="ph-bold ph-check text-emerald-600"></i> <span>Auto-Filled!</span>';
                
                setTimeout(() => {
                    btn.classList.remove('bg-emerald-50', 'text-emerald-800', 'border-emerald-250');
                    btn.classList.add('bg-primary-50', 'text-zinc-800', 'border-primary-200/60');
                    btn.innerHTML = '<i class="ph-bold ph-sparkle text-[10px]"></i> <span>Auto-Fill Info</span>';
                }, 2500);
            }
        } else {
            console.warn('Backend scrape failed. Attempting client-side bypass proxy...');
            if (btn) {
                btn.innerHTML = '<i class="ph ph-spinner animate-spin text-[10px]"></i> <span>Bypassing Bot...</span>';
            }
            
            // Try fetching through api.allorigins.win (public CORS proxy)
            fetch('https://api.allorigins.win/get?url=' + encodeURIComponent(url))
            .then(proxyRes => {
                if (!proxyRes.ok) throw new Error('Proxy server returned ' + proxyRes.status);
                return proxyRes.json();
            })
            .then(proxyData => {
                if (!proxyData || !proxyData.contents) {
                    throw new Error('Gagal mendapatkan isi halaman dari proxy.');
                }
                
                // Send this raw HTML content to our backend for parsing
                return fetch('/jobs/scrape-html', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        html: proxyData.contents,
                        url: url
                    })
                });
            })
            .then(res => res.json())
            .then(finalData => {
                if (finalData.success) {
                    if (finalData.company_name && window.Livewire) {
                        @this.set('company_name', finalData.company_name);
                    }
                    if (finalData.job_title && window.Livewire) {
                        @this.set('position', finalData.job_title);
                    }
                    if (finalData.location && window.Livewire) {
                        @this.set('location', finalData.location);
                        @this.call('parseLocation', finalData.location);
                    }

                    // Auto-detect platform from URL
                    let detectPlatform = '';
                    if (url.includes('linkedin.com')) {
                        detectPlatform = 'LinkedIn';
                    } else if (url.includes('jobstreet.com') || url.includes('seek.com')) {
                        detectPlatform = 'JobStreet';
                    } else if (url.includes('talentics.id')) {
                        detectPlatform = 'Talentics';
                    } else if (url.includes('dealls.com')) {
                        detectPlatform = 'Dealls';
                    } else if (url.includes('kalibrr.com')) {
                        detectPlatform = 'Kalibrr';
                    } else if (url.includes('glints.com')) {
                        detectPlatform = 'Glints';
                    }
                    if (detectPlatform && window.Livewire) {
                        @this.set('platform', detectPlatform);
                    }

                    if (btn) {
                        btn.disabled = false;
                        btn.classList.remove('bg-primary-50', 'text-zinc-800', 'border-primary-200/60');
                        btn.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-250');
                        btn.innerHTML = '<i class="ph-bold ph-check text-emerald-600"></i> <span>Auto-Filled!</span>';
                        
                        setTimeout(() => {
                            btn.classList.remove('bg-emerald-50', 'text-emerald-800', 'border-emerald-250');
                            btn.classList.add('bg-primary-50', 'text-zinc-800', 'border-primary-200/60');
                            btn.innerHTML = '<i class="ph-bold ph-sparkle text-[10px]"></i> <span>Auto-Fill Info</span>';
                        }, 2500);
                    }
                } else {
                    throw new Error(finalData.message || 'Gagal memproses data dari proxy.');
                }
            })
            .catch(proxyErr => {
                console.error('Proxy bypass failed:', proxyErr);
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ph-bold ph-sparkle text-[10px]"></i> <span>Auto-Fill Info</span>';
                }
                alert('Gagal mengambil data lowongan. Website memblokir server kami (403 Cloudflare).');
            });
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="ph-bold ph-sparkle text-[10px]"></i> <span>Auto-Fill Info</span>';
        }
        alert('Gagal mengambil data dari URL.');
    });
};
</script>
