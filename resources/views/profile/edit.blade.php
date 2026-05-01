<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Profile <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Settings</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Manage your account and professional identity</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

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
            <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-indigo-50/50 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-purple-50/50 rounded-full blur-[100px]"></div>
        </div>

        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8 relative z-10">
            
            {{-- Notifications --}}
            <div id="notifications" class="fixed top-24 right-8 z-[100] space-y-3 pointer-events-none">
                @foreach(['profile-updated' => 'Profile updated!', 'personal-info-updated' => 'Personal info saved!', 'password-updated' => 'Password changed!', 'photo-updated' => 'Photo updated!', 'photo-removed' => 'Photo removed!'] as $key => $msg)
                    @if (session('status') === $key)
                        <div class="notification-toast bg-slate-900 text-white rounded-2xl p-4 pl-6 pr-12 shadow-2xl flex items-center gap-3 pointer-events-auto animate-slide-in relative border border-white/10">
                            <i class="ph-bold ph-check-circle text-emerald-400 text-xl"></i>
                            <p class="text-xs font-black uppercase tracking-widest">{{ $msg }}</p>
                            <button onclick="this.parentElement.remove()" class="absolute right-4 text-slate-400 hover:text-white transition-colors">
                                <i class="ph-bold ph-x text-sm"></i>
                            </button>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- Sidebar --}}
                <div class="lg:col-span-4 space-y-6">
                    {{-- User Card --}}
                    <div class="bg-white rounded-[2.5rem] border border-slate-200/60 overflow-hidden shadow-sm relative group">
                        <div class="h-32 bg-gradient-to-br from-[#d983e4] via-purple-600 to-[#4e71c5] relative overflow-hidden">
                            <div class="absolute inset-0 bg-black/10"></div>
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl animate-pulse"></div>
                            <div class="absolute left-10 bottom-5 w-20 h-20 bg-indigo-400/20 rounded-full blur-xl"></div>
                        </div>
                        
                        <div class="px-8 pb-8 flex flex-col items-center">
                            <div class="relative -mt-16 mb-6 group/avatar">
                                <div class="w-32 h-32 rounded-[2.5rem] overflow-hidden bg-white shadow-2xl border-4 border-white ring-1 ring-slate-100 transition-transform duration-500 group-hover/avatar:scale-105">
                                    @if(Auth::user()->logo)
                                        <img src="{{ Storage::url(Auth::user()->logo) }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
                                            <i class="ph-fill ph-user text-5xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <button onclick="openProfilePhotoModal()" class="absolute -bottom-2 -right-2 w-11 h-11 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg hover:bg-indigo-600 hover:scale-110 transition-all border-4 border-white">
                                    <i class="ph-bold ph-camera-plus text-lg"></i>
                                </button>
                            </div>

                            <div class="text-center w-full">
                                <h3 class="text-xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $user->email }}</p>
                                
                                <div class="mt-6 pt-6 border-t border-slate-100 flex flex-col gap-3">
                                    @if($user->is_premium)
                                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-2xl p-4 flex items-center justify-between group/prem">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                                    <i class="ph-fill ph-crown text-amber-500 text-lg"></i>
                                                </div>
                                                <span class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Premium Plan</span>
                                            </div>
                                            <span class="text-[8px] font-black text-amber-500 bg-white px-2 py-1 rounded-full border border-amber-100 uppercase tracking-widest group-hover/prem:scale-110 transition-transform">ACTIVE</span>
                                        </div>
                                    @else
                                        <a href="{{ route('payment.index') }}" class="relative overflow-hidden group/upgrade bg-indigo-600 text-white rounded-2xl p-4 text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-indigo-100">
                                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover/upgrade:translate-x-full transition-transform duration-1000"></div>
                                            <i class="ph-bold ph-lightning"></i>
                                            Upgrade to Premium
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Profile Completeness --}}
                    <div class="bg-white rounded-3xl border border-slate-200/70 p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Profile Completeness</h4>
                            <span class="text-[10px] font-black {{ $percentage === 100 ? 'text-emerald-600 bg-emerald-50' : 'text-indigo-600 bg-indigo-50' }} px-2 py-0.5 rounded-full">{{ $percentage }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden mb-4">
                            <div class="h-full {{ $percentage === 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-indigo-500 to-purple-500' }} transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="space-y-1.5">
                            @php
                                $items = [
                                    ['Photo',    (bool)$user->logo],
                                    ['Bio',      (bool)($profile?->bio)],
                                    ['Phone',    (bool)($profile?->phone_number)],
                                    ['Location', (bool)($profile?->domicile)],
                                    ['LinkedIn', (bool)($profile?->linkedin_url)],
                                    ['Website',  (bool)($profile?->website_url)],
                                ];
                            @endphp
                            @foreach($items as [$label, $done])
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded-full flex items-center justify-center shrink-0 {{ $done ? 'bg-emerald-100' : 'bg-slate-100' }}">
                                    <i class="ph-bold {{ $done ? 'ph-check text-emerald-600' : 'ph-minus text-slate-300' }} text-[8px]"></i>
                                </div>
                                <span class="text-[11px] font-{{ $done ? 'bold text-slate-700' : 'medium text-slate-400' }}">{{ $label }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Navigation Tabs --}}
                    <div class="bg-white rounded-3xl border border-slate-200/70 p-2.5 shadow-sm">
                        <nav class="flex flex-col gap-1">
                            @foreach([
                                ['account',  'ph-user',               'Account Info'],
                                ['personal', 'ph-identification-card', 'Personal Info'],
                                ['security', 'ph-shield-check',       'Security'],
                            ] as [$tab, $icon, $label])
                            <button onclick="switchTab('{{ $tab }}')" id="tab-{{ $tab }}"
                                    class="tab-btn {{ $tab === 'account' ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                                <i class="ph-bold {{ $icon }} text-base"></i>
                                <span>{{ $label }}</span>
                            </button>
                            @endforeach
                            <div class="my-1.5 border-t border-slate-100"></div>
                            <button onclick="switchTab('danger')" id="tab-danger"
                                    class="tab-btn flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all hover:bg-rose-50 hover:text-rose-600">
                                <i class="ph-bold ph-warning-octagon text-base"></i>
                                <span>Danger Zone</span>
                            </button>
                        </nav>
                    </div>
                </div>

                {{-- Content Area --}}
                <div class="lg:col-span-8">
                    {{-- Account --}}
                    <div id="section-account" class="content-section">
                        <div class="bg-white rounded-[2.5rem] border border-slate-200/60 p-8 sm:p-10 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-indigo-50/30 rounded-full blur-3xl"></div>
                            <div class="relative">
                                <div class="flex items-center gap-5 mb-10">
                                    <div class="w-14 h-14 bg-indigo-50 rounded-[1.25rem] flex items-center justify-center text-indigo-600 shadow-inner">
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
                            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-purple-50/30 rounded-full blur-3xl"></div>
                            <div class="relative">
                                <div class="flex items-center gap-5 mb-10">
                                    <div class="w-14 h-14 bg-purple-50 rounded-[1.25rem] flex items-center justify-center text-purple-600 shadow-inner">
                                        <i class="ph-bold ph-fingerprint text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Personal Information</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Data for professional matching & CVs</p>
                                    </div>
                                </div>
                                
                                <form method="post" action="{{ route('profile.personal.update') }}" class="space-y-8">
                                    @csrf
                                    @method('patch')

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                        <div class="group/field space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/field:text-indigo-600 transition-colors">Phone Number</label>
                                            <div class="relative">
                                                <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-focus-within/field:bg-indigo-100 group-focus-within/field:text-indigo-600 transition-all">
                                                    <i class="ph-bold ph-phone"></i>
                                                </div>
                                                <input type="text" name="phone" value="{{ old('phone', $user->profile->phone_number ?? '') }}" class="w-full pl-16 pr-6 py-4 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all border-none ring-1 ring-slate-200">
                                            </div>
                                        </div>
                                        <div class="group/field space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/field:text-indigo-600 transition-colors">Domicile / Location</label>
                                            <div class="relative">
                                                <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-focus-within/field:bg-indigo-100 group-focus-within/field:text-indigo-600 transition-all">
                                                    <i class="ph-bold ph-map-pin"></i>
                                                </div>
                                                <input type="text" name="location" value="{{ old('location', $user->profile->domicile ?? '') }}" class="w-full pl-16 pr-6 py-4 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all border-none ring-1 ring-slate-200">
                                            </div>
                                        </div>
                                        <div class="group/field space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/field:text-indigo-600 transition-colors">LinkedIn URL</label>
                                            <div class="relative">
                                                <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-focus-within/field:bg-indigo-100 group-focus-within/field:text-indigo-600 transition-all">
                                                    <i class="ph-bold ph-linkedin-logo"></i>
                                                </div>
                                                <input type="url" name="linkedin" value="{{ old('linkedin', $user->profile->linkedin_url ?? '') }}" class="w-full pl-16 pr-6 py-4 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all border-none ring-1 ring-slate-200" placeholder="https://linkedin.com/in/...">
                                            </div>
                                        </div>
                                        <div class="group/field space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/field:text-indigo-600 transition-colors">Personal Website</label>
                                            <div class="relative">
                                                <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-focus-within/field:bg-indigo-100 group-focus-within/field:text-indigo-600 transition-all">
                                                    <i class="ph-bold ph-globe"></i>
                                                </div>
                                                <input type="url" name="website" value="{{ old('website', $user->profile->website_url ?? '') }}" class="w-full pl-16 pr-6 py-4 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all border-none ring-1 ring-slate-200" placeholder="https://...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="group/field space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/field:text-indigo-600 transition-colors">Professional Biography</label>
                                        <textarea name="bio" rows="6" class="w-full px-8 py-6 bg-slate-50 border-none ring-1 ring-slate-200 rounded-[2.5rem] text-sm font-medium text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all leading-relaxed" placeholder="Describe your professional background and aspirations...">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
                                    </div>

                                    <div class="flex justify-end pt-4">
                                        <button type="submit" class="group/btn px-10 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-indigo-600 transition-all shadow-xl shadow-slate-100 active:scale-95 flex items-center gap-3">
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
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500"></div>
            
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
                    <button type="button" onclick="uploadProfilePhoto()" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-indigo-600 transition-all flex items-center justify-center gap-3 active:scale-95 shadow-xl shadow-slate-100">
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
        .tab-btn { color: #94a3b8; border: 1px solid transparent; }
        .tab-btn:hover { background-color: #f8fafc; color: #64748b; }
        .tab-btn.active { 
            background-color: #0f172a; 
            color: white; 
            box-shadow: 0 15px 30px -5px rgba(15, 23, 42, 0.2); 
            transform: translateX(4px);
        }
        
        /* Premium Form Styling Overrides */
        .premium-form-wrapper input:not([type="checkbox"]):not([type="radio"]), 
        .premium-form-wrapper textarea,
        .premium-form-wrapper select {
            @apply w-full rounded-2xl border-none ring-1 ring-slate-200 bg-slate-50/50 py-4 px-6 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all !important;
        }
        
        .premium-form-wrapper label {
            @apply text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block !important;
        }
        
        .premium-form-wrapper button[type="submit"] {
            @apply px-10 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[2px] hover:bg-indigo-600 transition-all shadow-xl shadow-slate-100 active:scale-95 !important;
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
                    setTimeout(() => window.location.reload(), 1000);
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
                .then(data => { if (data.success) window.location.reload(); else alert(data.message); })
                .catch(() => alert('Failed to remove photo.'));
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Auto-hide notifications
            setTimeout(() => {
                document.querySelectorAll('.notification-toast').forEach(t => {
                    t.style.transition = 'opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                    t.style.opacity = '0';
                    t.style.transform = 'translateX(40px)';
                    setTimeout(() => t.remove(), 600);
                });
            }, 5000);
        });
    </script>
</x-app-layout>
