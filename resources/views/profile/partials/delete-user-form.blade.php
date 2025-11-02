<!-- Warning Banner -->
<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <div class="flex-1">
            <h4 class="text-sm font-semibold text-red-900 mb-1">Warning: This action cannot be undone</h4>
            <p class="text-sm text-red-700">
                Deleting your account will permanently remove all of your data, including:
            </p>
            <ul class="mt-2 text-sm text-red-700 space-y-1 list-disc list-inside">
                <li>Job applications and tracking history</li>
                <li>CV builder data and saved resumes</li>
                <li>Personal information and profile settings</li>
                <li>All associated files and documents</li>
            </ul>
            <p class="mt-3 text-sm font-medium text-red-800">
                Please download any important data before proceeding.
            </p>
        </div>
    </div>
</div>

<!-- Delete Button -->
<div class="flex justify-end">
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        <span>Delete My Account</span>
    </button>
</div>

<!-- Confirmation Modal -->
<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <div class="p-6">
        <!-- Modal Header -->
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Delete Account?</h2>
                <p class="text-sm text-gray-500">This action is permanent and irreversible</p>
            </div>
        </div>

        <!-- Modal Content -->
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-700">
                    Once your account is deleted, all of its resources and data will be permanently deleted. 
                    Please enter your password to confirm you would like to permanently delete your account.
                </p>
            </div>

            <!-- Password Confirmation -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Enter Your Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Your current password"
                    class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-sm @error('password', 'userDeletion') border-red-300 @enderror">
                @error('password', 'userDeletion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Modal Actions -->
            <div class="flex items-center justify-end space-x-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors duration-200">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>Yes, Delete Account</span>
                </button>
            </div>
        </form>
    </div>
</x-modal>
