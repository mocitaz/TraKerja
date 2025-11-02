<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="TraKerja Logo" 
                             class="w-5 h-5 sm:w-6 sm:h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            Profile Settings
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5 hidden sm:block">Manage your account and preferences</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-2 bg-green-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded-full">
                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-primary-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-primary-700">Live</span>
                </div>
                <div class="text-xs text-gray-400 hidden sm:block">
                    {{ now()->format('M d, Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Notifications -->
            <div id="notifications" class="mb-4 space-y-2">
            @if (session('status') === 'profile-updated')
                    <div class="notification-toast bg-green-50 border border-green-200 rounded-lg p-3 flex items-center shadow-sm animate-slide-in">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                        <p class="ml-3 text-sm font-medium text-green-800">Profile updated successfully!</p>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                </div>
            @endif

            @if (session('status') === 'personal-info-updated')
                    <div class="notification-toast bg-primary-50 border border-primary-200 rounded-lg p-3 flex items-center shadow-sm animate-slide-in">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                        <p class="ml-3 text-sm font-medium text-primary-800">Personal info updated!</p>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-primary-500 hover:text-primary-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                </div>
            @endif

            @if (session('status') === 'password-updated')
                    <div class="notification-toast bg-orange-50 border border-orange-200 rounded-lg p-3 flex items-center shadow-sm animate-slide-in">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        </div>
                        <p class="ml-3 text-sm font-medium text-orange-800">Password updated successfully!</p>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-orange-500 hover:text-orange-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6">
                <!-- Left Sidebar - Profile Card -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-4">
                        <!-- Profile Header with Gradient -->
                        <div class="relative h-24 sm:h-32 bg-gradient-to-br from-[#d983e4] via-[#8b5cf6] to-[#4e71c5]">
                            <div class="absolute inset-0 bg-black/10"></div>
                            <!-- Decorative circles -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                </div>

                        <!-- Profile Photo -->
                        <div class="relative px-6 pb-6">
                            <div class="flex flex-col items-center -mt-12">
                                <div class="relative group">
                                    <div class="w-24 h-24 rounded-2xl overflow-hidden bg-white shadow-xl border-4 border-white ring-2 ring-gray-100">
                                @if(Auth::user()->logo)
                                    <img src="{{ Storage::url(Auth::user()->logo) }}" 
                                         alt="Profile Photo" 
                                         class="w-full h-full object-cover">
                                @else
                                            <div class="w-full h-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                                <span class="text-white font-bold text-3xl">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <button onclick="openProfilePhotoModal()" 
                                            class="absolute -bottom-2 -right-2 w-10 h-10 bg-primary-600 hover:bg-primary-700 rounded-xl flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-110">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- User Info -->
                                <div class="text-center mt-4 w-full">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                                    
                                    <!-- Email Verification Badge -->
                                    <div class="flex items-center justify-center mt-2">
                                        @if($user->hasVerifiedEmail())
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Email Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                                Email Not Verified
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Premium Badge or Upgrade Button -->
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        @if($user->is_premium && $user->payment_status === 'paid')
                                            <!-- Premium Badge -->
                                            <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-xl p-3 mb-3">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                    <span class="text-white font-bold text-sm">Premium Member</span>
                                                </div>
                                                @if($user->premium_until)
                                                <p class="text-xs text-white/90 text-center mt-1">
                                                    Valid until {{ $user->premium_until->format('d M Y') }}
                                                </p>
                                                @endif
                                            </div>
                                        @else
                                            <!-- Upgrade to Premium Button -->
                                            <a href="{{ route('payment.index') }}" 
                                               class="block w-full bg-gradient-to-r from-primary-500 to-primary-700 hover:from-primary-600 hover:to-primary-800 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 mb-3">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                    <span class="text-sm">Upgrade to Premium</span>
                                                </div>
                                            </a>
                                        @endif
                                        
                                        <!-- Member Since -->
                                        <div class="flex items-center justify-center text-xs text-gray-500">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                            Member since {{ $user->created_at->format('M Y') }}
                        </div>
                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Tabs -->
                        <div class="border-t border-gray-100">
                            <nav class="flex flex-col p-2">
                                <button onclick="switchTab('account')" id="tab-account" class="tab-btn active flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Account Info</span>
                                </button>
                                <button onclick="switchTab('personal')" id="tab-personal" class="tab-btn flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Personal Info</span>
                                </button>
                                <button onclick="switchTab('security')" id="tab-security" class="tab-btn flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                    <span>Security</span>
                                </button>
                                <button onclick="switchTab('danger')" id="tab-danger" class="tab-btn flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span>Danger Zone</span>
                                </button>
                            </nav>
                        </div>
                    </div>
                            </div>

                <!-- Right Content Area -->
                <div class="lg:col-span-8">
                    <!-- Account Information Section -->
                    <div id="section-account" class="content-section">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Account Information</h3>
                                    <p class="text-sm text-gray-500">Update your account name and email</p>
                                </div>
                            </div>

                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                    
                    <!-- Personal Information Section -->
                    <div id="section-personal" class="content-section hidden">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                                    <p class="text-sm text-gray-500">Manage your personal details for CV</p>
                                </div>
                            </div>

                            <!-- Personal Info Form -->
                            <form method="post" action="{{ route('profile.personal.update') }}" class="space-y-4">
                                @csrf
                                @method('patch')

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Phone -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                               class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                                    </div>

                                    <!-- Location -->
                                    <div>
                                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                        <input type="text" name="location" id="location" value="{{ old('location', $user->location) }}"
                                               class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                                    </div>
                                            </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- LinkedIn -->
                                    <div>
                                        <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
                                        <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $user->linkedin) }}"
                                               placeholder="https://linkedin.com/in/username"
                                               class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                                    </div>

                                    <!-- Website -->
                                    <div>
                                        <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                                        <input type="url" name="website" id="website" value="{{ old('website', $user->website) }}"
                                               placeholder="https://yourwebsite.com"
                                               class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                                    </div>
                                </div>

                                <!-- Bio -->
                                <div>
                                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio / About</label>
                                    <textarea name="bio" id="bio" rows="4"
                                              class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm"
                                              placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                                    <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                        <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

                    <!-- Security Section -->
                    <div id="section-security" class="content-section hidden">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Security Settings</h3>
                                    <p class="text-sm text-gray-500">Keep your account secure</p>
                                </div>
                            </div>
                            
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                    
                    <!-- Danger Zone Section -->
                    <div id="section-danger" class="content-section hidden">
                        <div class="bg-white rounded-2xl shadow-sm border border-red-200 p-6">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Danger Zone</h3>
                                    <p class="text-sm text-gray-500">Irreversible and destructive actions</p>
                                </div>
                            </div>
                            
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>

    <!-- Profile Photo Modal -->
    <div id="profilePhotoModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4" onclick="if(event.target === this) closeProfilePhotoModal()">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all animate-scale-in">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Update Profile Photo</h3>
                <button onclick="closeProfilePhotoModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
            </div>

            <form id="photoUploadForm" method="POST" action="{{ route('profile-photo.upload') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                    <!-- Current Photo Preview -->
                <div class="flex justify-center">
                    <div class="w-32 h-32 rounded-2xl overflow-hidden bg-gray-100 shadow-lg">
                            @if(Auth::user()->logo)
                            <img src="{{ Storage::url(Auth::user()->logo) }}" 
                                 alt="Current Photo" 
                                 id="currentPhotoPreview"
                                     class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                <span class="text-white font-bold text-4xl">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                    </div>
                    </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Choose New Photo</label>
                    <input type="file" name="logo" accept="image/*" 
                           onchange="previewPhoto(event)"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                    <p class="mt-2 text-xs text-gray-500">JPG, PNG or GIF (MAX. 2MB)</p>
                    </div>

                <!-- Actions -->
                <div class="flex space-x-3 pt-4">
                    @if(Auth::user()->logo)
                        <button type="button" onclick="removeProfilePhoto()"
                                class="flex-1 px-4 py-2.5 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 font-medium transition-colors duration-200">
                            Remove Photo
                        </button>
                    @endif
                    <button type="button" onclick="uploadProfilePhoto()"
                            class="flex-1 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors duration-200">
                        Save Photo
                    </button>
                </div>
            </form>
            
            <!-- Upload Status -->
            <div id="uploadStatus" class="hidden mt-4"></div>
        </div>
    </div>

    <!-- Remove Photo Form (hidden) -->
    <form id="removePhotoForm" method="POST" action="{{ route('profile-photo.delete') }}" class="hidden">
        @csrf
        @method('delete')
    </form>

    <style>
        .tab-btn {
            color: #6b7280;
        }
        .tab-btn:hover {
            background-color: #f3f4f6;
            color: #374151;
        }
        .tab-btn.active {
            background: linear-gradient(135deg, #d983e4 0%, #8b5cf6 50%, #4e71c5 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.3);
        }
        .content-section {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes scale-in {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
        .animate-scale-in {
            animation: scale-in 0.2s ease-out;
        }
    </style>

    <script>
        // Tab Switching
        function switchTab(tabName) {
            // Hide all content sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected content section
            document.getElementById(`section-${tabName}`).classList.remove('hidden');

            // Add active class to selected tab
            document.getElementById(`tab-${tabName}`).classList.add('active');
        }

        // Profile Photo Modal
        function openProfilePhotoModal() {
            document.getElementById('profilePhotoModal').classList.remove('hidden');
            document.getElementById('profilePhotoModal').classList.add('flex');
        }

        function closeProfilePhotoModal() {
            document.getElementById('profilePhotoModal').classList.add('hidden');
            document.getElementById('profilePhotoModal').classList.remove('flex');
        }

        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('currentPhotoPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function uploadProfilePhoto() {
            const form = document.getElementById('photoUploadForm');
            const formData = new FormData(form);
            const statusDiv = document.getElementById('uploadStatus');
            
            // Show loading
            statusDiv.className = 'mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-center';
            statusDiv.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-blue-800">Uploading...</span>
            `;
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusDiv.className = 'mt-4 bg-green-50 border border-green-200 rounded-lg p-3 flex items-center';
                    statusDiv.innerHTML = `
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-green-800">${data.message}</span>
                    `;
                    // Reload page after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    statusDiv.className = 'mt-4 bg-red-50 border border-red-200 rounded-lg p-3 flex items-center';
                    statusDiv.innerHTML = `
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-sm text-red-800">${data.message}</span>
                    `;
                }
            })
            .catch(error => {
                statusDiv.className = 'mt-4 bg-red-50 border border-red-200 rounded-lg p-3 flex items-center';
                statusDiv.innerHTML = `
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="text-sm text-red-800">Upload failed. Please try again.</span>
                `;
            });
        }

        function removeProfilePhoto() {
            if (confirm('Are you sure you want to remove your profile photo?')) {
                const form = document.getElementById('removePhotoForm');
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeProfilePhotoModal();
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to remove photo');
                    }
                })
                .catch(error => {
                    alert('Failed to remove photo. Please try again.');
                });
            }
        }

        // Auto-dismiss notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.notification-toast').forEach(function(toast) {
                    toast.style.transition = 'opacity 0.3s, transform 0.3s';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(function() {
                        toast.remove();
                    }, 300);
                });
            }, 5000);
        });
    </script>
</x-app-layout>
