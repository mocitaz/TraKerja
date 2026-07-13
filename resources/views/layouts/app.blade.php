<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TraKerja') }}</title>
        


        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}?v=2">
        <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}?v=2">

        <!-- PWA Meta & Manifest -->
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="TraKerja">

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

            /* Livewire Navigation Progress Bar Custom Style */
            #nprogress .bar {
                background: #18181b !important;
                height: 3px !important;
            }
            #nprogress .peg {
                box-shadow: 0 0 10px #18181b, 0 0 5px #18181b !important;
            }
            #nprogress .spinner-icon {
                border-top-color: #18181b !important;
                border-left-color: #18181b !important;
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

            /* Customize Livewire Native Progress Bar to simple solid black without glow */
            .livewire-progress-bar {
                background-color: #000000 !important;
                box-shadow: none !important;
                height: 2px !important;
            }

            /* Snappy premium view transition */
            @media (prefers-reduced-motion: no-preference) {
                ::view-transition-old(root),
                ::view-transition-new(root) {
                    animation-duration: 180ms;
                    animation-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#fafafa] text-slate-900"
          x-data="{ mobileSidebarOpen: false }"
          @keydown.escape="mobileSidebarOpen = false">


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

            // View Transition API integration with Livewire
            document.addEventListener('livewire:navigating', (ev) => {
                if (!document.startViewTransition || !ev.detail || typeof ev.detail.beforeDomUpdate !== 'function') return;

                ev.detail.beforeDomUpdate((navigation) => {
                    return new Promise((resolve) => {
                        document.startViewTransition(async () => {
                            resolve();
                            await navigation.complete;
                        });
                    });
                });
            });

            // Register PWA Service Worker
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then(reg => {
                            reg.update();
                            console.log('PWA Service Worker registered!');
                        })
                        .catch(err => console.log('PWA Service Worker failed:', err));
                });
            }

            // Handle Livewire Navigation
            document.addEventListener('livewire:navigated', () => {
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
        {{-- Telegram Community Pop-Up Modal (Notion-Style Compact) --}}
        @if(session('show_telegram_popup'))
        <div x-data="{ open: true }" 
             x-show="open" 
             class="fixed inset-0 bg-zinc-950/45 backdrop-blur-xs z-[99999] flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-init="
                // Auto trigger confetti for delightful login experience
                setTimeout(() => {
                    confetti({
                        particleCount: 80,
                        spread: 60,
                        origin: { y: 0.7 }
                    });
                }, 300);
             ">
            <!-- Modal Body (Notion Styled) -->
            <div @click.away="open = false" 
                 class="bg-white rounded-xl border border-zinc-200/80 shadow-2xl max-w-[360px] w-full overflow-hidden transform transition-all p-6 text-center"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 translate-y-4"
                 x-transition:enter-end="scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="scale-100 translate-y-0"
                 x-transition:leave-end="scale-95 translate-y-4">
                
                <!-- Close Button -->
                <button @click="open = false" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-600 transition">
                    <i class="ph ph-x text-lg"></i>
                </button>

                <!-- Telegram Icon Circle -->
                <div class="w-12 h-12 rounded-full bg-blue-50/70 flex items-center justify-center mx-auto mb-4">
                    <i class="ph ph-telegram-logo text-blue-600 text-2xl"></i>
                </div>

                <!-- Text Content -->
                <h3 class="text-base font-bold text-zinc-900 tracking-tight leading-tight">Welcome Back!</h3>
                <p class="text-[11px] font-semibold text-zinc-450 mt-1 mb-5">
                    Connect with fellow job seekers and receive real-time job vacancy alerts in our Telegram community.
                </p>

                <!-- Action Buttons -->
                <div class="space-y-2">
                    <a href="https://t.me/HubTrakerja" target="_blank" rel="noopener noreferrer" @click="open = false" 
                       class="w-full py-2 bg-[#0066cc] hover:bg-[#0052a3] text-white text-xs font-bold rounded-lg transition-colors shadow-3xs flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-paper-plane-tilt text-xs"></i>
                        Join HubTrakerja
                    </a>
                    <button @click="open = false" 
                            class="w-full py-2 bg-white hover:bg-zinc-50 border border-zinc-200 text-zinc-600 text-xs font-bold rounded-lg transition-colors">
                        Go to Dashboard
                    </button>
                </div>
            </div>
        </div>
        @endif

        @stack('modals')
    </body>
</html>