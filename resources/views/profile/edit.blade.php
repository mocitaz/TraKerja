<x-app-layout>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @php
        $profile = $user->profile;
        $completeness = 0;
        $totalItems = 6;
        if($user->logo) $completeness++;
        if($profile?->bio) $completeness++;
        if($profile?->phone_number) $completeness++;
        if($profile?->domicile) $completeness++;
        if($profile?->linkedin_url) $completeness++;
        if($profile?->website_url) $completeness++;
        
        $percentage = round(($completeness / $totalItems) * 100);
    @endphp

    <div class="bg-[#f8fafc] min-h-screen pb-20 relative overflow-hidden">
        {{-- Subtle Ambient Background --}}
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
            <div class="absolute top-[-10%] right-[-10%] w-[60%] h-[60%] bg-primary-100/30 rounded-full blur-[140px]"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-[50%] h-[50%] bg-[#d983e4]/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8 relative z-10">
            
            {{-- Floating Pill Navigation (Moved out of topbar) --}}
            <div class="flex justify-center mb-10">
                <nav class="flex p-1.5 bg-white border border-slate-200/60 rounded-[2rem] shadow-sm">
                    @foreach([
                        ['account',  'ph-user',               'Identity'],
                        ['personal', 'ph-identification-card', 'Contact'],
                        ['security', 'ph-shield-check',       'Security'],
                    ] as [$tab, $icon, $label])
                    <button onclick="switchTab('{{ $tab }}')" id="tab-{{ $tab }}"
                            class="tab-btn {{ $tab === 'account' ? 'active' : '' }} flex items-center gap-2 px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all hover:bg-slate-50">
                        <i class="ph-bold {{ $icon }} text-base"></i>
                        <span>{{ $label }}</span>
                    </button>
                    @endforeach
                </nav>
            </div>

            {{-- Global Toast Trigger for Session Status (Fallback for direct access) --}}
            @if (session('status'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showProfileToast("{{ session('status') }}");
                    });
                </script>
            @endif

            <script>
                function showProfileToast(status) {
                    const statusMap = {
                        'profile-updated': { type: 'success', title: 'Identity Updated', message: 'Your basic profile information has been successfully saved.' },
                        'personal-info-updated': { type: 'success', title: 'Contact Saved', message: 'Your personal and contact details have been updated.' },
                        'password-updated': { type: 'success', title: 'Security Updated', message: 'Your account password has been changed successfully.' },
                        'photo-updated': { type: 'success', title: 'Photo Updated', message: 'Your profile identity photo has been updated.' },
                        'photo-removed': { type: 'info', title: 'Photo Removed', message: 'Your profile photo has been removed from your account.' },
                        'verification-link-sent': { type: 'info', title: 'Link Sent', message: 'A new verification link has been sent to your email.' }
                    };
                    
                    const config = statusMap[status];
                    if (config && typeof window.showToast === 'function') {
                        window.showToast(config.type, config.title, config.message);
                    }
                }
            </script>

            {{-- Bento Grid Layout --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                
                {{-- [BENTO 1] Hero Identity Card (Full Width) --}}
                <div class="md:col-span-12 group">
                    <div class="bg-white rounded-[3rem] border border-slate-200/60 p-10 shadow-sm hover:shadow-xl hover:shadow-primary-500/5 transition-all duration-500 relative overflow-hidden">
                        {{-- Decorative Background Elements --}}
                        <div class="absolute -right-20 -top-20 w-80 h-80 bg-gradient-to-br from-primary-100/40 via-[#d983e4]/10 to-transparent rounded-full blur-3xl group-hover:bg-primary-100/60 transition-colors duration-700"></div>
                        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-slate-50/80 rounded-full blur-3xl"></div>
                        
                        <div class="relative flex flex-col md:flex-row items-center gap-10">
                            {{-- Profile Aura Avatar --}}
                            <div class="relative group/avatar">
                                <div class="absolute inset-0 bg-primary-100 rounded-full animate-spin-slow opacity-20 scale-110"></div>
                                <svg class="absolute inset-[-10px] w-[calc(100%+20px)] h-[calc(100%+20px)] transform -rotate-90">
                                    <circle cx="50%" cy="50%" r="48%" stroke="currentColor" stroke-width="3" fill="transparent" class="text-slate-100" />
                                    <circle cx="50%" cy="50%" r="48%" stroke="currentColor" stroke-width="3" fill="transparent" class="text-primary-500 transition-all duration-1000" stroke-dasharray="100" stroke-dashoffset="{{ 100 - $percentage }}" stroke-linecap="round" />
                                </svg>
                                
                                <div class="relative w-36 h-36 rounded-full overflow-hidden bg-white shadow-2xl border-4 border-white transition-transform duration-500 group-hover/avatar:scale-105">
                                    @if(Auth::user()->logo)
                                        <img src="{{ Storage::url(Auth::user()->logo) }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
                                            <i class="ph-fill ph-user text-6xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <button onclick="openProfilePhotoModal()" class="absolute bottom-1 right-1 w-10 h-10 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg hover:bg-primary-600 transition-all border-4 border-white">
                                    <i class="ph-bold ph-camera text-base"></i>
                                </button>
                            </div>

                            <div class="flex-1 text-center md:text-left">
                                <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2">
                                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter">{{ $user->name }}</h3>
                                    @if($user->is_premium)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 border border-amber-100 rounded-full text-[9px] font-black uppercase tracking-widest self-center">
                                            <i class="ph-fill ph-crown"></i> PRO
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm font-bold text-slate-400 mb-6">{{ $user->email }}</p>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-slate-50/50 rounded-2xl p-4 border border-slate-100">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Completeness</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg font-black text-slate-800">{{ $percentage }}%</span>
                                            <div class="flex-1 h-1 bg-slate-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-primary-500" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50/50 rounded-2xl p-4 border border-slate-100">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Member Since</p>
                                        <p class="text-sm font-black text-slate-800">{{ Auth::user()->created_at->format('M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [BENTO 2] Skill Matrix (Radar Chart) --}}
                <div class="md:col-span-7">
                    <div class="bg-white rounded-[3rem] border border-slate-200/60 p-8 shadow-sm h-full relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h4 class="text-[10px] font-black text-primary-500 uppercase tracking-[0.3em] mb-1">Professional Matrix</h4>
                                <p class="text-lg font-black text-slate-800 tracking-tight">Skill Proficiency</p>
                            </div>
                            <div class="w-10 h-10 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-primary-500 transition-colors">
                                <i class="ph-bold ph-strategy text-lg"></i>
                            </div>
                        </div>

                        <div class="relative aspect-square max-h-[300px] mx-auto">
                            <canvas id="skillMatrixChart"></canvas>
                        </div>

                        {{-- Legend Placeholder or Small Stats --}}
                        <div class="mt-8 grid grid-cols-3 gap-4">
                            @php
                                $categories = $user->skills->groupBy('category');
                            @endphp
                            @foreach($categories->take(3) as $cat => $skills)
                                <div class="text-center">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $cat ?: 'General' }}</p>
                                    <p class="text-xs font-bold text-slate-700">{{ $skills->count() }} Skills</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- [BENTO 3] Quick Stats / Premium (Small) --}}
                <div class="md:col-span-5">
                    <div class="bg-slate-900 rounded-[3rem] p-8 shadow-xl relative overflow-hidden h-full flex flex-col justify-between group">
                        <div class="absolute right-0 top-0 w-48 h-48 bg-primary-500/30 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
                        <div class="absolute left-0 bottom-0 w-32 h-32 bg-[#d983e4]/20 rounded-full blur-2xl"></div>
                        
                        <div class="relative">
                            <h4 class="text-[10px] font-black text-primary-400 uppercase tracking-[0.3em] mb-2">Member Status</h4>
                            @if($user->is_premium)
                                <p class="text-2xl font-black text-white tracking-tight leading-tight">Elevated to<br>Premium Access</p>
                            @else
                                <p class="text-2xl font-black text-white tracking-tight leading-tight">Unlock Your<br>Full Potential</p>
                            @endif
                        </div>

                        <div class="mt-8 relative">
                            @if(!$user->is_premium)
                                <a href="{{ route('payment.index') }}" class="group/btn bg-white text-slate-900 h-14 rounded-2xl flex items-center justify-between px-6 font-black text-[10px] uppercase tracking-widest hover:bg-primary-50 transition-all">
                                    <span>Upgrade Now</span>
                                    <i class="ph-bold ph-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            @else
                                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 flex items-center justify-between">
                                    <span class="text-[9px] font-black text-white/60 uppercase tracking-widest">Active Plan</span>
                                    <span class="text-[9px] font-black text-primary-300 uppercase tracking-widest">Enterprise</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- [BENTO 4] Main Content Sections (Tabbed) --}}
                <div class="md:col-span-12 lg:col-span-12">
                    <div class="space-y-6">
                    {{-- Account --}}
                    <div id="section-account" class="content-section">
                        <div class="bg-white rounded-[2.5rem] border border-slate-200/60 p-8 sm:p-10 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-primary-50/30 rounded-full blur-3xl"></div>
                            <div class="relative">
                                <div class="flex items-center gap-5 mb-10">
                                    <div class="w-14 h-14 bg-primary-50 rounded-[1.25rem] flex items-center justify-center text-primary-600 shadow-inner">
                                        <i class="ph-bold ph-user-focus text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Account Information</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Basic identification details</p>
                                    </div>
                                </div>
                                <div class="premium-form-wrapper">
                                    @include('profile.partials.update-profile-information-form')
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Personal --}}
                    <div id="section-personal" class="content-section hidden">
                        <div class="bg-white rounded-[2.5rem] border border-slate-200/60 p-8 sm:p-10 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-primary-50/30 rounded-full blur-3xl"></div>
                            <div class="relative">
                                <div class="flex items-center gap-5 mb-10">
                                    <div class="w-14 h-14 bg-primary-50 rounded-[1.25rem] flex items-center justify-center text-primary-600 shadow-inner">
                                        <i class="ph-bold ph-fingerprint text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Personal Information</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Data for professional matching & CVs</p>
                                    </div>
                                </div>
                                
                                <form id="personalInfoForm" method="post" action="{{ route('profile.personal.update') }}" class="space-y-8">
                                    @csrf
                                    @method('patch')

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                        <div class="group/field space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/field:text-primary-600 transition-colors">Phone Number</label>
                                            <div class="relative">
                                                <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-focus-within/field:bg-primary-100 group-focus-within/field:text-primary-600 transition-all">
                                                    <i class="ph-bold ph-phone"></i>
                                                </div>
                                                <input type="text" name="phone" value="{{ old('phone', $user->profile->phone_number ?? '') }}" class="w-full pl-16 pr-6 py-4 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all border-none ring-1 ring-slate-200">
                                            </div>
                                        </div>
                                        <div class="group/field space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/field:text-primary-600 transition-colors">Domicile / Location</label>
                                            <div class="relative">
                                                <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-focus-within/field:bg-primary-100 group-focus-within/field:text-primary-600 transition-all">
                                                    <i class="ph-bold ph-map-pin"></i>
                                                </div>
                                                <input type="text" name="location" value="{{ old('location', $user->profile->domicile ?? '') }}" class="w-full pl-16 pr-6 py-4 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all border-none ring-1 ring-slate-200">
                                            </div>
                                        </div>
                                        <div class="group/field space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/field:text-primary-600 transition-colors">LinkedIn URL</label>
                                            <div class="relative">
                                                <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-focus-within/field:bg-primary-100 group-focus-within/field:text-primary-600 transition-all">
                                                    <i class="ph-bold ph-linkedin-logo"></i>
                                                </div>
                                                <input type="url" name="linkedin" value="{{ old('linkedin', $user->profile->linkedin_url ?? '') }}" class="w-full pl-16 pr-6 py-4 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all border-none ring-1 ring-slate-200" placeholder="https://linkedin.com/in/...">
                                            </div>
                                        </div>
                                        <div class="group/field space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/field:text-primary-600 transition-colors">Personal Website</label>
                                            <div class="relative">
                                                <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-focus-within/field:bg-primary-100 group-focus-within/field:text-primary-600 transition-all">
                                                    <i class="ph-bold ph-globe"></i>
                                                </div>
                                                <input type="url" name="website" value="{{ old('website', $user->profile->website_url ?? '') }}" class="w-full pl-16 pr-6 py-4 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all border-none ring-1 ring-slate-200" placeholder="https://...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="group/field space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/field:text-primary-600 transition-colors">Professional Biography</label>
                                        <textarea name="bio" rows="6" class="w-full px-8 py-6 bg-slate-50 border-none ring-1 ring-slate-200 rounded-[2.5rem] text-sm font-medium text-slate-700 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all leading-relaxed" placeholder="Describe your professional background and aspirations...">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
                                    </div>

                                    <div class="flex justify-end pt-4">
                                        <button type="submit" class="group/btn px-10 py-4 bg-primary-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 active:scale-95 flex items-center gap-3">
                                            <i class="ph-bold ph-check-circle text-lg group-hover/btn:scale-110 transition-transform"></i>
                                            Save Profile Details
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Security --}}
                    <div id="section-security" class="content-section hidden">
                        <div class="bg-white rounded-[2.5rem] border border-slate-200/60 p-8 sm:p-10 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-emerald-50/30 rounded-full blur-3xl"></div>
                            <div class="relative">
                                <div class="flex items-center gap-5 mb-10">
                                    <div class="w-14 h-14 bg-emerald-50 rounded-[1.25rem] flex items-center justify-center text-emerald-600 shadow-inner">
                                        <i class="ph-bold ph-shield-check text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Security Settings</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Keep your credentials safe</p>
                                    </div>
                                </div>
                                <div class="premium-form-wrapper">
                                    @include('profile.partials.update-password-form')
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Danger --}}
                    <div id="section-danger" class="content-section hidden">
                        <div class="bg-rose-50 rounded-[2.5rem] border border-rose-100 p-8 sm:p-10 shadow-sm">
                            <div class="flex items-center gap-5 mb-10">
                                <div class="w-14 h-14 bg-rose-100 rounded-[1.25rem] flex items-center justify-center text-rose-600 shadow-inner">
                                    <i class="ph-bold ph-warning-octagon text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Danger Zone</h3>
                                    <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mt-1">Irreversible and destructive actions</p>
                                </div>
                            </div>
                            <div class="premium-form-wrapper bg-white/50 rounded-[2rem] p-6 border border-rose-100/50">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile Photo Modal --}}
    <div id="profilePhotoModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md hidden items-center justify-center z-[1000] p-4" onclick="if(event.target === this) closeProfilePhotoModal()">
        <div class="bg-white rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.2)] max-w-md w-full p-10 transform transition-all animate-scale-in border border-slate-200/60 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary-500 via-primary-500 to-primary-500"></div>
            
            <div class="flex items-center justify-between mb-10">
                <div class="flex flex-col">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Update Identity</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Profile Photo Upload</p>
                </div>
                <button onclick="closeProfilePhotoModal()" class="w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-slate-100 transition-all text-slate-400 hover:text-slate-900">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form id="photoUploadForm" method="POST" action="{{ route('profile-photo.upload') }}" enctype="multipart/form-data" class="space-y-10">
                @csrf
                <div class="flex flex-col items-center">
                    <div class="w-40 h-40 rounded-[3rem] overflow-hidden bg-slate-50 shadow-inner mb-8 ring-1 ring-slate-100 group/preview relative">
                        @if(Auth::user()->logo)
                            <img src="{{ Storage::url(Auth::user()->logo) }}" alt="Avatar" id="currentPhotoPreview" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-200">
                                <i class="ph-fill ph-user text-7xl"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/preview:opacity-100 transition-opacity flex items-center justify-center">
                            <i class="ph-bold ph-image-square text-white text-3xl"></i>
                        </div>
                    </div>
                    
                    <label class="relative cursor-pointer">
                        <span class="px-6 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-[10px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-100 transition-all flex items-center gap-2">
                            <i class="ph-bold ph-folder-open"></i>
                            Browse Files
                        </span>
                        <input type="file" name="logo" accept="image/*" onchange="previewPhoto(event)" class="absolute inset-0 opacity-0 cursor-pointer">
                    </label>
                    <p class="mt-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest">JPG, PNG or GIF. Max 2MB.</p>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="button" onclick="uploadProfilePhoto()" class="w-full py-4 bg-primary-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-primary-700 transition-all flex items-center justify-center gap-3 active:scale-95 shadow-xl shadow-primary-100">
                        <i class="ph-bold ph-cloud-arrow-up text-lg"></i>
                        SAVE NEW IDENTITY
                    </button>
                    @if(Auth::user()->logo)
                        <button type="button" onclick="removeProfilePhoto()" class="w-full py-4 bg-rose-50 text-rose-600 rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-rose-100 transition-all flex items-center justify-center gap-3">
                            <i class="ph-bold ph-trash-simple text-lg"></i>
                            REMOVE CURRENT PHOTO
                        </button>
                    @endif
                </div>
            </form>
            <div id="uploadStatus" class="hidden mt-6"></div>
        </div>
    </div>

    <form id="removePhotoForm" method="POST" action="{{ route('profile-photo.delete') }}" class="hidden">@csrf @method('delete')</form>

    <style>
        .tab-btn { color: #94a3b8; }
        .tab-btn.active { 
            background-color: #f8fafc; 
            color: #0f172a; 
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Premium Form Styling Overrides */
        .premium-form-wrapper input:not([type="checkbox"]):not([type="radio"]), 
        .premium-form-wrapper textarea,
        .premium-form-wrapper select {
            @apply w-full rounded-2xl border-none ring-1 ring-slate-200 bg-slate-50/50 py-4 px-6 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all !important;
        }
        
        .premium-form-wrapper label {
            @apply text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block !important;
        }
        
        .premium-form-wrapper button[type="submit"] {
            @apply px-10 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-primary-600 transition-all shadow-xl shadow-slate-100 active:scale-95 !important;
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 12s linear infinite;
        }

        .content-section { animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slide-in { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes scale-in { from { opacity: 0; transform: scale(0.92); } to { opacity: 1; transform: scale(1); } }
        .animate-slide-in { animation: slide-in 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        .animate-scale-in { animation: scale-in 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); }
    </style>

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.content-section').forEach(s => s.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(`section-${tabName}`).classList.remove('hidden');
            document.getElementById(`tab-${tabName}`).classList.add('active');
            
            // Scroll to top of content on mobile
            if (window.innerWidth < 1024) {
                document.getElementById(`section-${tabName}`).scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function openProfilePhotoModal() { 
            const modal = document.getElementById('profilePhotoModal');
            modal.classList.remove('hidden'); 
            modal.classList.add('flex'); 
            document.body.style.overflow = 'hidden';
        }
        
        function closeProfilePhotoModal() { 
            const modal = document.getElementById('profilePhotoModal');
            modal.classList.add('hidden'); 
            modal.classList.remove('flex'); 
            document.body.style.overflow = 'auto';
        }

        function previewPhoto(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('currentPhotoPreview');
                    if (preview) preview.src = e.target.result;
                    else {
                        const div = document.querySelector('#photoUploadForm .w-40');
                        div.innerHTML = `<img src="${e.target.result}" id="currentPhotoPreview" class="w-full h-full object-cover">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        function uploadProfilePhoto() {
            const form = document.getElementById('photoUploadForm');
            const formData = new FormData(form);
            const statusDiv = document.getElementById('uploadStatus');
            
            statusDiv.className = 'mt-6 bg-slate-900 rounded-2xl p-4 flex items-center gap-3 text-white animate-pulse';
            statusDiv.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-xl"></i><span class="text-[10px] font-black uppercase tracking-widest">Processing identity update...</span>`;
            statusDiv.classList.remove('hidden');
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    statusDiv.className = 'mt-6 bg-emerald-500 rounded-2xl p-4 flex items-center gap-3 text-white shadow-lg shadow-emerald-100';
                    statusDiv.innerHTML = `<i class="ph-bold ph-check-circle text-xl"></i><span class="text-[10px] font-black uppercase tracking-widest">${data.message}</span>`;
                    showProfileToast('photo-updated');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    statusDiv.className = 'mt-6 bg-rose-500 rounded-2xl p-4 flex items-center gap-3 text-white shadow-lg shadow-rose-100';
                    statusDiv.innerHTML = `<i class="ph-bold ph-warning text-xl"></i><span class="text-[10px] font-black uppercase tracking-widest">${data.message}</span>`;
                }
            })
            .catch(() => {
                statusDiv.className = 'mt-6 bg-rose-500 rounded-2xl p-4 flex items-center gap-3 text-white';
                statusDiv.innerHTML = `<i class="ph-bold ph-warning text-xl"></i><span class="text-[10px] font-black uppercase tracking-widest">Network error occurred.</span>`;
            });
        }

        function removeProfilePhoto() {
            if (confirm('Are you sure you want to remove your profile photo?')) {
                const form = document.getElementById('removePhotoForm');
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                })
                .then(r => r.json())
                .then(data => { 
                    if (data.success) {
                        showProfileToast('photo-removed');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        window.showToast('error', 'Removal Failed', data.message);
                    }
                })
                .catch(() => alert('Failed to remove photo.'));
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Skill Matrix Chart
            const ctx = document.getElementById('skillMatrixChart');
            if (ctx) {
                @php
                    $skillData = $user->skills->take(6);
                    $labels = $skillData->pluck('skill_name')->toArray();
                    $values = $skillData->pluck('proficiency_level')->toArray();
                    
                    // Fallback if no skills
                    if(empty($labels)) {
                        $labels = ['Technical', 'Communication', 'Leadership', 'Creativity', 'Problem Solving', 'Organization'];
                        $values = [80, 70, 90, 65, 85, 75];
                    }
                @endphp

                new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: {!! json_encode($labels) !!},
                        datasets: [{
                            label: 'Proficiency',
                            data: {!! json_encode($values) !!},
                            fill: true,
                            backgroundColor: 'rgba(165, 112, 240, 0.15)',
                            borderColor: '#a570f0',
                            pointBackgroundColor: '#a570f0',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#a570f0',
                            borderWidth: 2,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                angleLines: { display: true, color: 'rgba(0,0,0,0.05)' },
                                grid: { color: 'rgba(0,0,0,0.05)' },
                                pointLabels: {
                                    font: { size: 9, weight: '900', family: 'Inter' },
                                    color: '#94a3b8'
                                },
                                ticks: { display: false, stepSize: 20 },
                                min: 0,
                                max: 100
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }

            // Auto-hide notifications
            setTimeout(() => {
                document.querySelectorAll('.notification-toast').forEach(t => {
                    t.style.transition = 'opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                    t.style.opacity = '0';
                    t.style.transform = 'translateX(40px)';
                    setTimeout(() => t.remove(), 600);
                });
            }, 5000);

            // AJAX Form Handling
            const profileFormIds = ['identityUpdateForm', 'personalInfoForm', 'passwordUpdateForm'];

            profileFormIds.forEach(id => {
                const form = document.getElementById(id);
                if (!form) return;

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const btn = form.querySelector('button[type="submit"]');
                    const originalBtnHtml = btn.innerHTML;
                    const formData = new FormData(form);
                    
                    // Show loading state
                    btn.disabled = true;
                    btn.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-lg"></i><span>Updating...</span>`;

                    fetch(form.action, {
                        method: 'POST', // Always POST for Laravel with _method field
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        btn.disabled = false;
                        btn.innerHTML = originalBtnHtml;

                        if (data.success || data.status) {
                            showProfileToast(data.status || 'profile-updated');
                            
                            // Clear password fields if it was a password update
                            if (id === 'passwordUpdateForm') {
                                form.reset();
                                if (typeof checkPasswordStrength === 'function') checkPasswordStrength();
                            }

                            // Clear previous error messages
                            form.querySelectorAll('.validation-error').forEach(el => el.remove());
                        }
                    })
                    .catch(error => {
                        console.error('Profile Update Error:', error);
                        btn.disabled = false;
                        btn.innerHTML = originalBtnHtml;
                        
                        if (error.errors) {
                            // Show first validation error in a toast
                            const firstKey = Object.keys(error.errors)[0];
                            window.showToast('error', 'Validation Error', error.errors[firstKey][0]);
                            
                            // Optionally show inline errors
                            Object.keys(error.errors).forEach(key => {
                                const input = form.querySelector(`[name="${key}"]`);
                                if (input) {
                                    // Remove existing error if any
                                    const existing = input.parentElement.querySelector('.validation-error');
                                    if (existing) existing.remove();
                                    
                                    const errEl = document.createElement('p');
                                    errEl.className = 'validation-error mt-1 text-xs text-rose-500 font-bold uppercase tracking-wider';
                                    errEl.textContent = error.errors[key][0];
                                    input.parentElement.appendChild(errEl);
                                }
                            });
                        } else {
                            window.showToast('error', 'Update Failed', 'An unexpected error occurred. Please try again.');
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
