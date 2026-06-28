<x-app-layout>
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

    <div class="bg-[#fafafa] min-h-screen pb-16 relative overflow-hidden">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6 relative z-10">
            
            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph ph-user-circle text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">Account Profile</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60">Settings</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Manage your personal settings, password, and profile identity details.</p>
                    </div>
                </div>
            </div>

            {{-- Global Toast Trigger for Session Status --}}
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
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                {{-- Left Column: Identity Card & Sticky Sidebar Nav --}}
                <div class="lg:col-span-4 space-y-5 lg:sticky lg:top-20 self-start">
                    {{-- User Profile Card --}}
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-5 shadow-3xs flex flex-col items-center text-center">
                        {{-- Profile Avatar with Inline Hover Upload --}}
                        <div class="relative shrink-0 mb-4 group/avatar cursor-pointer">
                            <div class="relative w-24 h-24 rounded-full overflow-hidden bg-zinc-50 border border-zinc-200 shadow-inner transition-all flex items-center justify-center">
                                @if(Auth::user()->logo)
                                    <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" id="avatarImageDisplay" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-zinc-300 bg-zinc-50" id="avatarPlaceholderDisplay">
                                        <i class="ph ph-user text-4xl"></i>
                                    </div>
                                @endif

                                {{-- Hover Overlay --}}
                                <div onclick="document.getElementById('inlineAvatarInput').click()" class="absolute inset-0 bg-black/60 opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-200 flex flex-col items-center justify-center text-white gap-1 select-none">
                                    <i class="ph ph-camera text-base"></i>
                                    <span class="text-[8px] font-bold uppercase tracking-wider">Change</span>
                                </div>
                            </div>
                            
                            {{-- Remove Photo Option if user has custom logo --}}
                            @if(Auth::user()->logo)
                                <button onclick="removeProfilePhotoInline(event)" title="Remove Profile Photo" class="absolute -bottom-1 -right-1 w-6 h-6 bg-rose-600 text-white rounded-full flex items-center justify-center shadow-md hover:bg-rose-700 transition-colors border border-white focus:outline-none z-10">
                                    <i class="ph ph-trash text-[11px]"></i>
                                </button>
                            @else
                                <div onclick="document.getElementById('inlineAvatarInput').click()" class="absolute -bottom-1 -right-1 w-6 h-6 bg-zinc-900 text-white rounded-full flex items-center justify-center shadow-md hover:bg-zinc-800 transition-colors border border-white z-10">
                                    <i class="ph ph-plus text-[11px]"></i>
                                </div>
                            @endif

                            {{-- Hidden file input --}}
                            <input type="file" id="inlineAvatarInput" accept="image/*" class="hidden" onchange="uploadAvatarInline(this)">
                        </div>

                        <div class="w-full">
                            <div class="flex items-center justify-center gap-1.5 mb-1">
                                <h3 class="text-sm font-extrabold text-zinc-850 tracking-tight">{{ $user->name }}</h3>
                                @if($user->is_premium)
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.2 bg-amber-50 text-amber-600 border border-amber-250/70 rounded text-[8.5px] font-black uppercase tracking-wider leading-none">
                                        <i class="ph ph-crown text-xs"></i> PRO
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] font-semibold text-zinc-400 mb-4">{{ $user->email }}</p>

                            <div class="space-y-3 pt-3.5 border-t border-zinc-100 w-full text-left">
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Profile Completeness</span>
                                        <span class="text-[10px] font-extrabold text-zinc-800 leading-none">{{ $percentage }}%</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-zinc-100 rounded-full overflow-hidden border border-zinc-200/20">
                                        <div class="h-full bg-zinc-700 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-[10px] font-semibold pt-1">
                                    <span class="text-zinc-400">Member Since</span>
                                    <span class="text-zinc-750 font-bold">{{ Auth::user()->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- macOS-style Sidebar Tab Switcher --}}
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-2 shadow-3xs space-y-1">
                        <button onclick="switchTab('profile')" id="tab-profile"
                                class="tab-btn active w-full flex items-center justify-between px-3 py-2.5 text-[11px] font-bold rounded-md transition-all focus:outline-none">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-user-focus text-sm"></i>
                                <span>Profile Details</span>
                            </div>
                            <i class="ph ph-caret-right text-[10px] opacity-60"></i>
                        </button>
                        <button onclick="switchTab('security')" id="tab-security"
                                class="tab-btn w-full flex items-center justify-between px-3 py-2.5 text-[11px] font-bold rounded-md transition-all focus:outline-none">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-shield-check text-sm"></i>
                                <span>Security & Control</span>
                            </div>
                            <i class="ph ph-caret-right text-[10px] opacity-60"></i>
                        </button>
                    </div>
                </div>

                {{-- Right Column: Content Section (Tabs content) --}}
                <div class="lg:col-span-8">
                    
                    {{-- TAB 1: PROFILE DETAILS --}}
                    <div id="section-profile" class="content-section space-y-6">
                        {{-- Card 1: Basic credentials --}}
                        <div class="bg-white rounded-lg border border-zinc-200/60 p-5 shadow-3xs">
                            <div class="flex items-center gap-2 mb-4 pb-2.5 border-b border-zinc-100">
                                <i class="ph ph-user-circle text-zinc-500 text-sm"></i>
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Basic Account Credentials</h3>
                            </div>
                            <div class="premium-form-wrapper">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>

                        {{-- Card 2: Personal details --}}
                        <div class="bg-white rounded-lg border border-zinc-200/60 p-5 shadow-3xs">
                            <div class="flex items-center gap-2 mb-4 pb-2.5 border-b border-zinc-100">
                                <i class="ph ph-identification-card text-zinc-500 text-sm"></i>
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Personal & Social Links</h3>
                            </div>
                            <div class="premium-form-wrapper">
                                <form method="post" action="{{ route('profile.personal.update') }}" class="space-y-4" id="personalInfoForm">
                                    @csrf
                                    @method('patch')

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <label class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Phone / WhatsApp</label>
                                            <input type="text" name="phone_number" value="{{ old('phone_number', $user->profile->phone_number ?? '') }}" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none" placeholder="+62 812-3456-7890">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Domicile / City</label>
                                            <input type="text" name="domicile" value="{{ old('domicile', $user->profile->domicile ?? '') }}" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none" placeholder="Jakarta, Indonesia">
                                        </div>
                                    </div>

                                    <div class="space-y-1">
                                        <label class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Headline / Role Title</label>
                                        <input type="text" name="headline" value="{{ old('headline', $user->profile->headline ?? '') }}" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none" placeholder="Senior Product Designer | Full-Stack Architect">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <label class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">LinkedIn URL</label>
                                            <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $user->profile->linkedin_url ?? '') }}" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none" placeholder="https://linkedin.com/in/username">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Personal Portfolio / Website</label>
                                            <input type="url" name="website_url" value="{{ old('website_url', $user->profile->website_url ?? '') }}" class="w-full px-3 py-1.5 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-semibold text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none" placeholder="https://yourportfolio.com">
                                        </div>
                                    </div>

                                    <div class="space-y-1">
                                        <label class="text-[9.5px] font-bold text-zinc-400 uppercase tracking-wider pl-0.5">Professional Biography</label>
                                        <textarea name="bio" rows="4" class="w-full px-3 py-2 bg-zinc-50/50 border border-zinc-200 rounded-md text-[11px] font-medium text-zinc-700 focus:ring-1 focus:ring-zinc-400 focus:border-zinc-400 focus:bg-white transition-colors outline-none resize-none leading-relaxed" placeholder="Describe your professional background and aspirations...">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
                                    </div>

                                    <div class="flex justify-end pt-3 border-t border-zinc-100">
                                        <button type="submit" class="px-3.5 py-1.5 bg-primary-50 text-zinc-800 border border-primary-200/60 hover:bg-primary-100 rounded-md text-[10px] font-bold uppercase tracking-wider transition-all flex items-center gap-1 shadow-3xs focus:outline-none active:scale-97">
                                            <i class="ph ph-check text-xs"></i>
                                            <span>Save Profile Details</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 2: SECURITY & CONTROL --}}
                    <div id="section-security" class="content-section space-y-6 hidden">
                        {{-- Card 1: Change password --}}
                        <div class="bg-white rounded-lg border border-zinc-200/60 p-5 shadow-3xs">
                            <div class="flex items-center gap-2 mb-4 pb-2.5 border-b border-zinc-100">
                                <i class="ph ph-shield-check text-zinc-500 text-sm"></i>
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Update Authentication Security</h3>
                            </div>
                            <div class="premium-form-wrapper">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>

                        {{-- Card 2: Danger Zone --}}
                        <div class="bg-white rounded-lg border border-rose-200/60 p-5 shadow-3xs">
                            <div class="flex items-center gap-2 mb-4 pb-2.5 border-b border-rose-100 text-rose-600">
                                <i class="ph ph-warning-octagon text-sm"></i>
                                <h3 class="text-xs font-bold text-zinc-800 tracking-tight">Danger Zone</h3>
                            </div>
                            <div class="premium-form-wrapper">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



    <style>
        .tab-btn {
            color: #71717a;
            border: 1px solid transparent;
        }
        .tab-btn.active { 
            background-color: #f4f4f5; 
            color: #18181b; 
            border-color: #e4e4e7;
            font-weight: 800;
        }
        .tab-btn:not(.active):hover {
            background-color: #fafafa;
            color: #18181b;
        }
        
        /* Notion-Inspired Form Styling Overrides */
        .premium-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]), 
        .premium-form-wrapper textarea,
        .premium-form-wrapper select {
            width: 100% !important;
            border-radius: 6px !important;
            border: 1px solid #e4e4e7 !important;
            background-color: rgba(250, 250, 250, 0.5) !important;
            padding: 6px 10px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            color: #3f3f46 !important;
            transition: all 0.15s ease !important;
            outline: none !important;
        }
        .premium-form-wrapper input.pl-9 {
            padding-left: 36px !important;
        }
        .premium-form-wrapper input.pr-9 {
            padding-right: 36px !important;
        }
        .premium-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):focus, 
        .premium-form-wrapper textarea:focus,
        .premium-form-wrapper select:focus {
            background-color: #ffffff !important;
            border-color: #a1a1aa !important;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
        }
        
        .premium-form-wrapper label {
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            font-size: 9.5px !important;
            font-weight: 700 !important;
            color: #a1a1aa !important;
            margin-left: 2px !important;
            margin-bottom: 4px !important;
            display: block !important;
        }
        
        .premium-form-wrapper button[type="submit"] {
            width: auto !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            padding: 6px 14px !important;
            background-color: #f5f3ff !important;
            color: #27272a !important;
            border: 1px solid rgba(221, 214, 254, 0.8) !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            border-radius: 6px !important;
            transition: all 0.15s ease !important;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,0.02) !important;
        }
        .premium-form-wrapper button[type="submit"]:hover {
            background-color: #ede9fe !important;
            color: #18181b !important;
        }

        .content-section { animation: fadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
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

        function uploadAvatarInline(input) {
            const file = input.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('logo', file);

            const overlay = input.parentElement.querySelector('.bg-black\\/60');
            const originalHTML = overlay.innerHTML;
            overlay.innerHTML = `<span class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>`;
            overlay.style.opacity = '1';

            fetch("{{ route('profile-photo.upload') }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showProfileToast('photo-updated');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    overlay.innerHTML = originalHTML;
                    overlay.style.opacity = '';
                    window.showToast('error', 'Upload Failed', data.message);
                }
            })
            .catch(() => {
                overlay.innerHTML = originalHTML;
                overlay.style.opacity = '';
                window.showToast('error', 'Network Error', 'An error occurred during upload.');
            });
        }

        function removeProfilePhotoInline(event) {
            event.stopPropagation();
            if (confirm('Are you sure you want to remove your profile photo?')) {
                const formData = new FormData();
                formData.append('_method', 'DELETE');

                fetch("{{ route('profile-photo.delete') }}", {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                })
                .then(r => r.json())
                .then(data => { 
                    if (data.success) {
                        showProfileToast('photo-removed');
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        window.showToast('error', 'Removal Failed', data.message);
                    }
                })
                .catch(() => window.showToast('error', 'Error', 'Failed to remove photo.'));
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
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
                    btn.innerHTML = `<span class="w-3 h-3 border-2 border-slate-400 border-t-white rounded-full animate-spin shrink-0"></span><span>Saving…</span>`;

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
                                    errEl.className = 'validation-error mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider';
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

    <!-- Confirmation Modal for Delete User -->
    @push('modals')
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-5">
            <!-- Modal Header -->
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-8 h-8 bg-red-50 border border-red-200 rounded flex items-center justify-center shrink-0">
                    <i class="ph ph-warning-octagon text-red-600 text-base"></i>
                </div>
                <div>
                    <h2 class="text-xs font-bold text-slate-800 leading-tight">Delete Account?</h2>
                    <p class="text-[9px] text-slate-450 mt-0.5">This action is permanent and irreversible</p>
                </div>
            </div>

            <!-- Modal Content -->
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="bg-slate-50 border border-slate-200 rounded p-3 mb-4">
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Once your account is deleted, all of its resources and data will be permanently deleted. 
                        Please enter your password to confirm you would like to permanently delete your account.
                    </p>
                </div>

                <!-- Password Confirmation -->
                <div class="mb-4 premium-form-wrapper">
                    <label for="password" class="block text-[9.5px] font-bold text-slate-450 uppercase tracking-wider pl-0.5 mb-1.5">
                        Enter Your Password
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Your current password"
                        class="w-full rounded-md border border-zinc-200 bg-slate-50 py-1.5 px-3 text-xs font-semibold text-zinc-700 outline-none @error('password', 'userDeletion') border-red-300 @enderror">
                    @error('password', 'userDeletion')
                        <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2">
                    <button
                        type="button"
                        x-on:click="$dispatch('close')"
                        class="px-3.5 py-1.5 border border-slate-200 text-slate-700 rounded-md hover:bg-slate-50 text-[10px] font-bold uppercase tracking-wider shadow-3xs transition-colors focus:outline-none">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-750 text-white rounded-md text-[10px] font-bold uppercase tracking-wider shadow-3xs transition-colors flex items-center gap-1.5 focus:outline-none">
                        <i class="ph ph-trash text-sm"></i>
                        <span>Yes, Delete Account</span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
    @endpush

</x-app-layout>
