@props(['id', 'title', 'message', 'confirmText' => 'Confirm', 'cancelText' => 'Cancel', 'type' => 'danger'])

<div id="{{ $id }}" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="{{ $id }}Content">
            <div class="p-6">
                <!-- Icon -->
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full
                    @if($type === 'danger') bg-red-100 @elseif($type === 'success') bg-green-100 @else bg-primary-100 @endif">
                    @if($type === 'danger')
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    @elseif($type === 'success')
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </div>

                <!-- Title -->
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">{{ $title }}</h3>
                
                <!-- Message -->
                <p class="text-gray-600 text-center mb-6">{{ $message }}</p>

                <!-- Buttons -->
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="closeModal('{{ $id }}')"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200">
                        {{ $cancelText }}
                    </button>
                    <button type="button" 
                            onclick="confirmAction()"
                            class="flex-1 px-4 py-3 rounded-xl font-medium text-white transition-all duration-200 transform hover:scale-105
                                @if($type === 'danger') bg-red-600 hover:bg-red-700 @elseif($type === 'success') bg-green-600 hover:bg-green-700 @else bg-primary-600 hover:bg-primary-700 @endif">
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
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = document.getElementById(modalId + 'Content');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
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
