<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('patch')

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <input id="name" name="name" type="text" 
               value="{{ old('name', $user->name) }}" 
               required autofocus autocomplete="name"
               class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm @error('name') border-red-300 @enderror">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <input id="email" name="email" type="email" 
               value="{{ old('email', $user->email) }}" 
               required autocomplete="username"
               class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm @error('email') border-red-300 @enderror">
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-yellow-800">
                            Your email address is unverified.
                            <button form="send-verification" class="font-medium underline hover:no-underline">
                                Click here to re-send verification email
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-medium text-green-600">
                                ✓ A new verification link has been sent!
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Submit -->
    <div class="flex items-center justify-end pt-4 border-t border-gray-100">
        <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>Save Changes</span>
        </button>
    </div>
</form>
