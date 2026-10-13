@props(['id', 'title', 'message', 'confirmText' => 'Confirm', 'cancelText' => 'Cancel', 'type' => 'danger'])

<div id="{{ $id }}" data-dialog-native="closeModal" role="dialog" aria-modal="true" aria-label="{{ $title }}" tabindex="-1" data-dialog-argument="{{ $id }}" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-[#1e1f20] border border-transparent dark:border-white/10 rounded-3xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="{{ $id }}Content">
            <div class="p-6">
                <!-- Icon -->
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full
                    @if($type === 'danger') bg-red-100 dark:bg-rose-950/40 @elseif($type === 'success') bg-green-100 dark:bg-emerald-950/40 @else bg-[#2579ca]/10 dark:bg-blue-950/40 @endif">
                    @if($type === 'danger')
                        <svg class="w-8 h-8 text-red-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    @elseif($type === 'success')
                        <svg class="w-8 h-8 text-green-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg class="w-8 h-8 text-[#2579ca] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </div>

                <!-- Title -->
                <h3 class="text-xl font-bold text-gray-900 dark:text-[#f0f4f9] text-center mb-2">{{ $title }}</h3>
                
                <!-- Message -->
                <p class="text-gray-600 dark:text-[#94a3b8] text-center mb-6">{{ $message }}</p>

                <!-- Buttons -->
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="closeModal('{{ $id }}')"
                            class="flex-1 px-4 py-3 border border-gray-300 dark:border-white/10 rounded-xl text-gray-700 dark:text-[#f0f4f9] font-medium hover:bg-gray-50 dark:hover:bg-[#282a2c] transition-colors duration-200">
                        {{ $cancelText }}
                    </button>
                    <button type="button" 
                            onclick="confirmAction()"
                            class="flex-1 px-4 py-3 rounded-xl font-medium text-white transition-all duration-200 transform hover:scale-105
                                @if($type === 'danger') bg-red-600 hover:bg-red-700 @elseif($type === 'success') bg-green-600 hover:bg-green-700 @else bg-[#2579ca] hover:bg-[#1e65ab] @endif">
                        {{ $confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = document.getElementById(modalId + 'Content');
        
        // Animate modal out
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');

        }, 300);
    }

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = document.getElementById(modalId + 'Content');
        modal.classList.remove('hidden');

        
        // Animate modal in
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('backdrop-blur-sm')) {
            const modalId = e.target.id;
            if (modalId) {
                closeModal(modalId);
            }
        }
    });
</script>
