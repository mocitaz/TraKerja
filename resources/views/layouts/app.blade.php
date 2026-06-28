<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TraKerja') }}</title>
        


        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|inter:300,400,500,600,700,800,900|plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Icons -->
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <!-- Confetti Library -->
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            /* Premium Global Scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
            ::-webkit-scrollbar-track {
                background: transparent;
            }
            ::-webkit-scrollbar-thumb {
                background: #e2e8f0;
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            /* Custom Selection */
            ::selection {
                background-color: rgba(99, 102, 241, 0.2);
                color: #4f46e5;
            }



            /* Smooth transition for theme colors */
            body {
                transition: background-color 0.3s ease;
            }

            /* Skeleton Shimmer Animation */
            @keyframes shimmer {
                0% { background-position: -1000px 0; }
                100% { background-position: 1000px 0; }
            }
            .skeleton {
                background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
                background-size: 1000px 100%;
                animation: shimmer 2s infinite linear;
            }

            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#fafafa] text-slate-900"
          x-data="{ mobileSidebarOpen: false }"
          @keydown.escape="mobileSidebarOpen = false">
        
        {{-- Premium Notion-Style Top Progress Line Loader --}}
        <div id="page-loader-bar" class="fixed top-0 left-0 right-0 h-[2px] z-[99999] pointer-events-none bg-black opacity-0" style="width: 0%; will-change: width, opacity;"></div>


        <div class="h-screen flex overflow-hidden">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Sidebar Overlay (Mobile) -->
            <div x-show="mobileSidebarOpen" 
                 x-transition.opacity.duration.300ms
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 lg:hidden" style="display: none;"></div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 relative overflow-hidden">
                @include('layouts.navigation', ['header' => $header ?? null])

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    {{ $slot }}
                </main>


            </div>
        </div>


        @include('components.toast-notification')
        @livewireScripts
        
        {{-- Premium Confirmation Modal (Linear-Style Left-Aligned) --}}
        <div id="confirmation-modal" class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs hidden z-[10001] flex items-center justify-center p-4 transition-all duration-300 pointer-events-none">
            <div class="bg-white rounded-lg border border-zinc-200/60 shadow-lg max-w-[400px] w-full flex flex-col overflow-hidden transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto p-5">
                
                <div class="flex items-start gap-4">
                    {{-- Warning Icon Container --}}
                    <div class="w-10 h-10 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shrink-0 shadow-4xs">
                        <i class="ph-bold ph-trash-simple text-lg"></i>
                    </div>
                    
                    {{-- Title and Message --}}
                    <div class="flex-1 min-w-0 space-y-1.5">
                        <h3 id="confirm-title" class="text-sm font-bold text-zinc-900 tracking-tight">Delete Application?</h3>
                        <p id="confirm-message" class="text-xs font-medium text-zinc-500 leading-relaxed">Are you sure you want to proceed with this action? This cannot be undone.</p>
                    </div>
                </div>

                {{-- Action Row --}}
                <div class="flex items-center justify-end gap-2.5 mt-5 pt-4 border-t border-zinc-100">
                    <button type="button" onclick="closeConfirmModal()" class="px-3.5 h-8 bg-white hover:bg-zinc-50 border border-zinc-200 text-zinc-650 text-xs font-bold rounded-md active:scale-97 transition-all">Cancel</button>
                    <button type="button" id="confirm-button" class="px-4 h-8 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-md active:scale-97 transition-all shadow-sm shadow-rose-500/10">Confirm</button>
                </div>
            </div>
        </div>

        <script>
            var activeConfirmCallback = activeConfirmCallback || null;

            function openConfirmModal(title, message, btnText, callback) {
                const modal = document.getElementById('confirmation-modal');
                const content = modal.querySelector('div');
                
                document.getElementById('confirm-title').innerText = title;
                document.getElementById('confirm-message').innerText = message;
                document.getElementById('confirm-button').innerText = btnText;
                
                activeConfirmCallback = callback;
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                }, 10);
            }

            function closeConfirmModal() {
                const modal = document.getElementById('confirmation-modal');
                const content = modal.querySelector('div');
                
                content.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }

            document.getElementById('confirm-button').onclick = function() {
                if (activeConfirmCallback) activeConfirmCallback();
                closeConfirmModal();
            };

            function confirmLogout(formId = 'logout-form') {
                if (typeof window.openConfirmModal === 'function') {
                    window.openConfirmModal(
                        'Sign Out?',
                        'Are you sure you want to end your current session? You will need to sign in again to access your tracker.',
                        'Sign Out Now',
                        function() {
                            const form = document.getElementById(formId) || document.getElementById('logout-form');
                            if (form) form.submit();
                        }
                    );
                } else {
                    if (confirm('Are you sure you want to sign out?')) {
                        const form = document.getElementById(formId) || document.getElementById('logout-form');
                        if (form) form.submit();
                    }
                }
            }

            // Listen for Livewire confirm-action and confetti
            document.addEventListener('livewire:init', () => {
                Livewire.on('confirm-action', (event) => {
                    const data = Array.isArray(event) ? event[0] : event;
                    openConfirmModal(data.title, data.message, data.btnText, () => {
                        Livewire.dispatch(data.onConfirm, data.params || {});
                    });
                });

                Livewire.on('confetti', () => {
                    const duration = 3 * 1000;
                    const animationEnd = Date.now() + duration;
                    const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 99999 };

                    const randomInRange = (min, max) => Math.random() * (max - min) + min;

                    const interval = setInterval(function() {
                        const timeLeft = animationEnd - Date.now();

                        if (timeLeft <= 0) {
                            return clearInterval(interval);
                        }

                        const particleCount = 50 * (timeLeft / duration);
                        confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } });
                        confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
                    }, 250);
                });
            });



            document.addEventListener('alpine:init', () => {
                Alpine.store('sidebar', {
                    open: false,
                    toggle() {
                        this.open = !this.open;
                    },
                    close() {
                        this.open = false;
                    }
                });
            });

            // Magnetic Button Logic
            function initMagneticButtons() {
                const buttons = document.querySelectorAll('.magnetic-btn');
                buttons.forEach(btn => {
                    btn.addEventListener('mousemove', (e) => {
                        const position = btn.getBoundingClientRect();
                        const x = e.pageX - position.left - position.width / 2;
                        const y = e.pageY - position.top - position.height / 2;

                        btn.style.transform = `translate(${x * 0.2}px, ${y * 0.4}px)`;
                        if (btn.children[0]) {
                            btn.children[0].style.transform = `translate(${x * 0.1}px, ${y * 0.2}px)`;
                        }
                    });

                    btn.addEventListener('mouseout', () => {
                        btn.style.transform = 'translate(0px, 0px)';
                        if (btn.children[0]) {
                            btn.children[0].style.transform = 'translate(0px, 0px)';
                        }
                    });
                });
            }

            window.topLoaderTimer = window.topLoaderTimer || null;

            function startTopLoader() {
                const loader = document.getElementById('page-loader-bar');
                if (!loader) return;
                if (window.topLoaderTimer) clearTimeout(window.topLoaderTimer);

                loader.style.transition = 'none';
                loader.style.width = '0%';
                loader.style.opacity = '1';
                void loader.offsetWidth;

                loader.style.transition = 'width 400ms cubic-bezier(0.4, 0, 0.2, 1), opacity 200ms ease';
                loader.style.width = '70%';
            }

            function finishTopLoader() {
                const loader = document.getElementById('page-loader-bar');
                if (!loader) return;
                if (window.topLoaderTimer) clearTimeout(window.topLoaderTimer);

                loader.style.transition = 'width 150ms ease-out, opacity 200ms ease';
                loader.style.width = '100%';

                window.topLoaderTimer = setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.width = '0%';
                    }, 200);
                }, 150);
            }

            // Handle Livewire Navigation
            document.addEventListener('livewire:navigate', startTopLoader);
            document.addEventListener('livewire:navigated', () => {
                finishTopLoader();
                initMagneticButtons();
                if (window.Alpine && Alpine.store('sidebar')) {
                    Alpine.store('sidebar').close();
                }
            });

            document.addEventListener('DOMContentLoaded', initMagneticButtons);

        </script>

        <style>
            .magnetic-btn {
                transition: transform 0.2s cubic-bezier(0.33, 1, 0.68, 1);
            }
        </style>
        @stack('modals')
    </body>
</html>