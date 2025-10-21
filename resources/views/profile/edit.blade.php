<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <img src="{{ asset('storage/logos/icon.png') }}" 
                             alt="TraKerja Logo" 
                             class="w-6 h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-[#0056B3] to-[#28A745] bg-clip-text text-transparent">
                            Profile Settings
        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Smart Tracking untuk Job Seeker</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-2 bg-green-50 px-3 py-1.5 rounded-full">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-xs font-medium text-green-700">Live</span>
                </div>
                <div class="text-xs text-gray-400">
                    {{ now()->format('M d, Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-[#0056B3] to-[#28A745] px-8 py-6">
                    <div class="flex items-center space-x-6">
                        <!-- Profile Photo -->
                        <div class="relative">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden bg-white/20 backdrop-blur-sm border-2 border-white/30">
                                @if(Auth::user()->logo)
                                    <img src="{{ Storage::url(Auth::user()->logo) }}" 
                                         alt="Profile Photo" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-white/20 flex items-center justify-center">
                                        <span class="text-white font-bold text-2xl">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <button onclick="openProfilePhotoModal()" 
                                    class="absolute -bottom-1 -right-1 w-6 h-6 bg-white rounded-full flex items-center justify-center shadow-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- User Info -->
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                            <p class="text-white/80 mt-1">{{ $user->email }}</p>
                            <div class="flex items-center space-x-4 mt-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-300 rounded-full"></div>
                                    <span class="text-sm text-white/90 font-medium">Active</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm text-white/70">Member since {{ $user->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-[#0056B3] rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                                <p class="text-sm text-gray-500">Update your name and email</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="post" action="{{ route('profile.update') }}" class="p-6">
                        @csrf
                        @method('patch')

                        <div class="space-y-5">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $user->name) }}"
                                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-all duration-200 @error('name') border-red-300 @enderror">
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email', $user->email) }}"
                                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-all duration-200 @error('email') border-red-300 @enderror">
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Save Button -->
                            <div class="flex justify-end pt-2">
                                <button type="submit" 
                                        class="px-6 py-3 bg-[#0056B3] text-white rounded-xl hover:bg-[#003d82] focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:ring-offset-2 transition-all duration-200 font-medium">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Security Settings -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Security Settings</h3>
                                <p class="text-sm text-gray-500">Change your password</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="post" action="{{ route('password.update') }}" class="p-6" id="passwordForm">
                        @csrf
                        @method('put')

                        <div class="space-y-5">
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Password
                                </label>
                                <input type="password" 
                                       name="current_password" 
                                       id="current_password"
                                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-all duration-200 @error('current_password') border-red-300 @enderror"
                                       oninput="validateCurrentPassword()">
                                @error('current_password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <div id="currentPasswordStatus" class="mt-2 text-sm hidden"></div>
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    New Password
                                </label>
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-all duration-200 @error('password') border-red-300 @enderror"
                                       oninput="validateNewPassword(this.value)"
                                       disabled>
                                @error('password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                
                                <!-- Password Requirements -->
                                <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                                    <div class="flex items-center space-x-2" id="req-length">
                                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-600">8+ characters</span>
                                    </div>
                                    <div class="flex items-center space-x-2" id="req-uppercase">
                                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-600">Uppercase</span>
                                    </div>
                                    <div class="flex items-center space-x-2" id="req-lowercase">
                                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-600">Lowercase</span>
                                    </div>
                                    <div class="flex items-center space-x-2" id="req-number">
                                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-600">Number</span>
                                    </div>
                                    <div class="flex items-center space-x-2 col-span-2" id="req-symbol">
                                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-600">Special character</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password
                                </label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation"
                                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent transition-all duration-200"
                                       oninput="validatePasswordMatch()"
                                       disabled>
                                <div id="passwordMatchStatus" class="mt-2 text-sm hidden"></div>
                            </div>

                            <!-- Save Button -->
                            <div class="flex justify-end pt-2">
                                <button type="submit" 
                                        id="updatePasswordBtn"
                                        class="px-6 py-3 bg-[#0056B3] text-white rounded-xl hover:bg-[#003d82] focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:ring-offset-2 transition-all duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                        disabled>
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="mt-8">
                <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-red-200 bg-red-50">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-red-900">Danger Zone</h3>
                                <p class="text-sm text-red-700">Permanent actions that cannot be undone</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-base font-medium text-gray-900">Delete Account</h4>
                                <p class="text-sm text-gray-600 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                            </div>
                            <button type="button" 
                                    onclick="confirmDeleteAccount()"
                                    class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>

    <!-- Profile Photo Upload Modal -->
    <div id="profilePhotoModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="photoModalContent">
            <div class="bg-gradient-to-r from-[#0056B3] to-[#28A745] px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Update Profile Photo</h3>
                    </div>
                    <button onclick="closeProfilePhotoModal()" class="text-white/80 hover:text-white transition-colors p-1 hover:bg-white/10 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <form id="profilePhotoForm" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Current Photo Preview -->
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden border-2 border-gray-200 shadow-sm">
                            @if(Auth::user()->logo)
                                <img id="profilePhotoPreview" 
                                     src="{{ Storage::url(Auth::user()->logo) }}" 
                                     alt="Profile Photo Preview" 
                                     class="w-full h-full object-cover">
                            @else
                                <div id="profilePhotoPreview" class="w-full h-full bg-gradient-to-br from-[#0056B3] to-[#28A745] flex items-center justify-center">
                                    <span class="text-white font-semibold text-2xl">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Current Profile Photo</p>
                    </div>

                    <!-- File Input -->
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Choose New Photo
                        </label>
                        <div class="relative">
                            <input type="file" 
                                   id="logo" 
                                   name="logo" 
                                   accept="image/*" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-[#0056B3] file:to-[#28A745] file:text-white hover:file:shadow-lg border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#0056B3] focus:border-transparent">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3 pt-4">
                        <button type="button" 
                                onclick="closeProfilePhotoModal()" 
                                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0056B3] transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-2.5 bg-[#0056B3] text-white rounded-lg text-sm font-medium hover:bg-[#003d82] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0056B3] transition-all duration-200">
                            Upload Photo
                        </button>
                    </div>

                    <!-- Delete Photo Button -->
                    @if(Auth::user()->logo)
                        <div class="pt-2">
                            <button type="button" 
                                    onclick="deleteProfilePhoto()" 
                                    class="w-full px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                                Remove Current Photo
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="notification" class="fixed top-4 right-4 z-50 transform transition-all duration-300 translate-x-full">
        <div class="bg-white rounded-lg shadow-lg border-l-4 p-4 max-w-sm">
            <div class="flex items-center">
                <div id="notificationIcon" class="flex-shrink-0">
                    <!-- Icon will be inserted here -->
                </div>
                <div class="ml-3">
                    <p id="notificationMessage" class="text-sm font-medium text-gray-900"></p>
                </div>
                <button onclick="hideNotification()" class="ml-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentPasswordValid = false;
        let newPasswordValid = false;
        let passwordMatch = false;

        function validateCurrentPassword() {
            const currentPassword = document.getElementById('current_password').value;
            const statusDiv = document.getElementById('currentPasswordStatus');
            const newPasswordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            
            if (currentPassword.length > 0) {
                // Simulate current password validation
                // In real implementation, you would make an AJAX call to verify
                fetch('{{ route("password.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        current_password: currentPassword
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        currentPasswordValid = true;
                        statusDiv.innerHTML = '<span class="text-green-600">✓ Current password is correct</span>';
                        statusDiv.classList.remove('hidden');
                        newPasswordInput.disabled = false;
                        confirmPasswordInput.disabled = false;
                        updatePasswordButton();
                    } else {
                        currentPasswordValid = false;
                        statusDiv.innerHTML = '<span class="text-red-600">✗ Current password is incorrect</span>';
                        statusDiv.classList.remove('hidden');
                        newPasswordInput.disabled = true;
                        confirmPasswordInput.disabled = true;
                        updatePasswordButton();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    currentPasswordValid = false;
                    statusDiv.innerHTML = '<span class="text-red-600">✗ Error verifying password</span>';
                    statusDiv.classList.remove('hidden');
                    newPasswordInput.disabled = true;
                    confirmPasswordInput.disabled = true;
                    updatePasswordButton();
                });
            } else {
                currentPasswordValid = false;
                statusDiv.classList.add('hidden');
                newPasswordInput.disabled = true;
                confirmPasswordInput.disabled = true;
                updatePasswordButton();
            }
        }

        function validateNewPassword(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                symbol: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };

            // Update visual indicators
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(`req-${req}`);
                const icon = element.querySelector('svg');
                const text = element.querySelector('span');
                
                if (requirements[req]) {
                    icon.classList.remove('text-gray-400');
                    icon.classList.add('text-[#28A745]');
                    text.classList.remove('text-gray-600');
                    text.classList.add('text-[#28A745]');
                } else {
                    icon.classList.remove('text-[#28A745]');
                    icon.classList.add('text-gray-400');
                    text.classList.remove('text-[#28A745]');
                    text.classList.add('text-gray-600');
                }
            });

            // Check if all requirements are met
            newPasswordValid = Object.values(requirements).every(req => req);
            
            // Validate password match if confirm password is filled
            if (document.getElementById('password_confirmation').value) {
                validatePasswordMatch();
            }
            
            updatePasswordButton();
        }

        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const statusDiv = document.getElementById('passwordMatchStatus');
            
            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    passwordMatch = true;
                    statusDiv.innerHTML = '<span class="text-green-600">✓ Passwords match</span>';
                    statusDiv.classList.remove('hidden');
                } else {
                    passwordMatch = false;
                    statusDiv.innerHTML = '<span class="text-red-600">✗ Passwords do not match</span>';
                    statusDiv.classList.remove('hidden');
                }
            } else {
                passwordMatch = false;
                statusDiv.classList.add('hidden');
            }
            
            updatePasswordButton();
        }

        function updatePasswordButton() {
            const submitBtn = document.getElementById('updatePasswordBtn');
            if (currentPasswordValid && newPasswordValid && passwordMatch) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        function openProfilePhotoModal() {
            const modal = document.getElementById('profilePhotoModal');
            const content = document.getElementById('photoModalContent');
            modal.classList.remove('hidden');
            
            // Animate modal in
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeProfilePhotoModal() {
            const modal = document.getElementById('profilePhotoModal');
            const content = document.getElementById('photoModalContent');
            
            // Animate modal out
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Notification functions
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notificationMessage');
            const iconEl = document.getElementById('notificationIcon');
            
            messageEl.textContent = message;
            
            // Set icon and colors based on type
            let iconHtml = '';
            let borderColor = '';
            
            switch(type) {
                case 'success':
                    iconHtml = '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    borderColor = 'border-green-500';
                    break;
                case 'error':
                    iconHtml = '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    borderColor = 'border-red-500';
                    break;
                case 'warning':
                    iconHtml = '<svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
                    borderColor = 'border-yellow-500';
                    break;
                default:
                    iconHtml = '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    borderColor = 'border-blue-500';
            }
            
            iconEl.innerHTML = iconHtml;
            notification.querySelector('.border-l-4').className = `border-l-4 ${borderColor}`;
            
            // Show notification
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                hideNotification();
            }, 5000);
        }

        function hideNotification() {
            const notification = document.getElementById('notification');
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
        }

        // Preview photo when file is selected
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePhotoPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle photo upload
        document.getElementById('profilePhotoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'Uploading...';
            submitBtn.disabled = true;

            fetch('{{ route("profile-photo.upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification('Profile photo uploaded successfully!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showNotification(data.message || 'Error uploading photo', 'error');
                    console.error('Upload error:', data);
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                showNotification('Network error: ' + error.message, 'error');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        // Handle photo deletion
        function deleteProfilePhoto() {
            if (confirm('Are you sure you want to remove your profile photo?')) {
                fetch('{{ route("profile-photo.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showNotification('Profile photo removed successfully!', 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showNotification(data.message || 'Error removing photo', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Network error: ' + error.message, 'error');
                });
            }
        }

        // Confirm delete account
        function confirmDeleteAccount() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                document.getElementById('delete-account-form').submit();
            }
        }
    </script>
</x-app-layout>