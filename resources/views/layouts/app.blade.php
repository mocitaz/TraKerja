<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TraKerja') }}</title>
        
        <!-- View Transitions API (For smooth cross-fades in modern browsers) -->
        <meta name="view-transition" content="same-origin" />

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
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

            /* Global Page Transitions */
            @keyframes pageEnter {
                from { 
                    opacity: 0; 
                    transform: translateY(10px); 
                }
                to { 
                    opacity: 1; 
                    transform: translateY(0); 
                }
            }

            .page-animate-enter {
                animation: pageEnter 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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
        </style>
    </head>
    <body class="font-sans antialiased bg-[#F8FAFC] text-slate-900"
          x-data="{ mobileSidebarOpen: false }"
          @keydown.escape="mobileSidebarOpen = false">
        
        {{-- Premium Page Loader --}}
        <div id="page-loader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-[#F8FAFC]/95 backdrop-blur-xl transition-all duration-700">
            <div class="relative flex flex-col items-center">
                {{-- Minimalist Circular Progress Container --}}
                <div class="relative flex items-center justify-center">
                    {{-- Rotating Track --}}
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle cx="64" cy="64" r="60" stroke="currentColor" stroke-width="1.5" fill="transparent" class="text-slate-100" />
                        <circle cx="64" cy="64" r="60" stroke="currentColor" stroke-width="1.5" fill="transparent" class="text-primary-500 animate-progress-ring" stroke-dasharray="377" stroke-dashoffset="377" stroke-linecap="round" />
                    </svg>
                    
                    {{-- TraKerja Icon --}}
                    <div class="absolute w-20 h-20 bg-white rounded-full shadow-[0_15px_35px_rgba(0,0,0,0.05)] flex items-center justify-center border border-slate-100 transition-transform duration-500 hover:scale-105">
                        <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-10 h-10 object-contain animate-pulse-subtle">
                    </div>
                </div>
                
                {{-- Professional Branding --}}
                <div class="mt-10 flex flex-col items-center">
                    <h2 class="text-[14px] font-black text-slate-800 uppercase tracking-[0.6em] leading-none">TraKerja</h2>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="h-[1px] w-4 bg-slate-200"></span>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.3em]">Elevating Identity</p>
                        <span class="h-[1px] w-4 bg-slate-200"></span>
                    </div>
                </div>
            </div>
        </div>

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
                <main class="flex-1 overflow-y-auto page-animate-enter">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                @php($noFooterRoutes = ['login', 'register', 'password.request', 'password.reset', 'verification.notice', 'legal.terms', 'legal.privacy'])
                @unless(in_array(Route::currentRouteName(), $noFooterRoutes))
                    <div class="mt-auto px-4 sm:px-6 lg:px-8 pb-6">
                        <footer class="bg-white/50 backdrop-blur-md border border-slate-200/60 rounded-[2rem] p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-white rounded-lg shadow-sm border border-slate-100 flex items-center justify-center">
                                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-5 h-5 object-contain">
                                </div>
                                <span class="text-sm font-black text-slate-900 tracking-tight">TraKerja</span>
                            </div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center md:text-right">
                                © {{ date('Y') }} TraKerja. PT Teknalogi Transformasi Digital . All rights reserved.
                            </p>
                        </footer>
                    </div>
                @endunless
            </div>
        </div>

        {{-- Premium Toast Container --}}
        <div id="toast-container" class="fixed bottom-8 right-8 z-[10000] flex flex-col gap-3 pointer-events-none"></div>

        @livewireScripts
        
        {{-- Premium Confirmation Modal (Aligned with Job Modal) --}}
        <div id="confirmation-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xl hidden z-[10001] flex items-center justify-center p-4 transition-all duration-300 pointer-events-none">
            <div class="bg-white rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] max-w-sm w-full flex flex-col overflow-hidden border border-slate-100 transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto">
                <!-- Modal Header: Match Add/Edit Style -->
                <div class="bg-white px-6 py-5 text-slate-900 flex justify-between items-center border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm">
                            <img src="{{ asset('images/icon.png') }}" alt="TraKerja" class="w-6 h-6 object-contain">
                        </div>
                        <div>
                            <h3 id="confirm-title" class="text-sm font-black tracking-tight">Confirm Action</h3>
                            <p class="text-slate-400 text-[8px] font-black uppercase tracking-widest mt-0.5">Career Growth Tracking</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-rose-100/50">
                        <i class="ph-fill ph-trash text-rose-500 text-3xl"></i>
                    </div>
                    <p id="confirm-message" class="text-[11px] font-bold text-slate-500 leading-relaxed mb-8 px-4">Are you sure you want to proceed with this action? This cannot be undone.</p>
                    <div class="flex items-center gap-3">
                        <button onclick="closeConfirmModal()" class="flex-1 px-6 py-3.5 bg-slate-50 text-slate-400 text-[10px] font-black rounded-xl hover:bg-slate-100 transition-all uppercase tracking-widest">Cancel</button>
                        <button id="confirm-button" class="flex-1 px-6 py-3.5 bg-rose-600 text-white text-[10px] font-black rounded-xl hover:bg-rose-700 transition-all shadow-xl shadow-rose-100 active:scale-95 uppercase tracking-widest">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let activeConfirmCallback = null;

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

            // Listen for Livewire confirm-action
            document.addEventListener('livewire:init', () => {
                Livewire.on('confirm-action', (event) => {
                    const data = Array.isArray(event) ? event[0] : event;
                    openConfirmModal(data.title, data.message, data.btnText, () => {
                        Livewire.dispatch(data.onConfirm, data.params || {});
                    });
                });
            });

            // Toast Notification System
            function showToast(type, title, message, duration = 4000) {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                
                const icons = {
                    success: 'ph-fill ph-check-circle text-emerald-500',
                    error: 'ph-fill ph-warning-circle text-rose-500',
                    info: 'ph-fill ph-info text-indigo-500',
                    warning: 'ph-fill ph-warning text-amber-500'
                };

                const bgColors = {
                    success: 'bg-emerald-50 border-emerald-100',
                    error: 'bg-rose-50 border-rose-100',
                    info: 'bg-indigo-50 border-indigo-100',
                    warning: 'bg-amber-50 border-amber-100'
                };

                toast.className = `flex items-start gap-4 p-4 rounded-[1.25rem] border shadow-[0_20px_40px_-12px_rgba(0,0,0,0.1)] backdrop-blur-md pointer-events-auto transition-all duration-500 transform translate-x-12 opacity-0 ${bgColors[type] || bgColors.info}`;
                toast.style.minWidth = '320px';
                toast.style.maxWidth = '420px';

                toast.innerHTML = `
                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center shrink-0">
                        <i class="ph-bold ${icons[type] || icons.info} text-xl"></i>
                    </div>
                    <div class="flex-1 pt-0.5">
                        <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest">${title}</h4>
                        <p class="text-[10px] font-bold text-slate-500 mt-1 leading-relaxed">${message}</p>
                    </div>
                    <button class="text-slate-300 hover:text-slate-600 transition-colors pt-1">
                        <i class="ph-bold ph-x text-xs"></i>
                    </button>
                `;

                container.appendChild(toast);

                // Animate In
                setTimeout(() => {
                    toast.classList.remove('translate-x-12', 'opacity-0');
                }, 10);

                const removeToast = () => {
                    toast.classList.add('translate-x-12', 'opacity-0');
                    setTimeout(() => toast.remove(), 500);
                };

                toast.querySelector('button').onclick = removeToast;
                if (duration > 0) setTimeout(removeToast, duration);
            }

            // Listen for Livewire showNotification
            document.addEventListener('livewire:init', () => {
                Livewire.on('showNotification', (event) => {
                    // Support both object and spread arguments
                    const data = Array.isArray(event) ? event[0] : event;
                    showToast(data.type, data.title, data.message, data.duration);
                });

                // Listen for Confetti Event
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
                        // since particles fall down, start a bit higher than random
                        confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } });
                        confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
                    }, 250);
                });
            });

            // Page & View Transitions
            window.addEventListener('view-switched', (e) => {
                const container = document.getElementById(`${e.detail.type}-view-container`);
                if (container) {
                    container.classList.add('animate-view-fade-in');
                    setTimeout(() => container.classList.remove('animate-view-fade-in'), 600);
                }
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
                const buttons = document.querySelectorAll('.magnetic-btn, [onclick^="switchView"]');
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

            function hidePageLoader() {
                const loader = document.getElementById('page-loader');
                if (loader) {
                    loader.classList.add('opacity-0');
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 700);
                }
            }

            function showPageLoader() {
                const loader = document.getElementById('page-loader');
                if (loader) {
                    loader.style.display = 'flex';
                    loader.classList.remove('opacity-0');
                }
            }

            // Initial hide
            window.addEventListener('load', () => setTimeout(hidePageLoader, 2300));
            document.addEventListener('DOMContentLoaded', () => setTimeout(hidePageLoader, 2300));

            // Handle Livewire Navigation
            document.addEventListener('livewire:navigate', showPageLoader);
            document.addEventListener('livewire:navigated', () => {
                setTimeout(hidePageLoader, 2300);
                initMagneticButtons();
                if (window.Alpine && Alpine.store('sidebar')) {
                    Alpine.store('sidebar').close();
                }
            });

            document.addEventListener('DOMContentLoaded', initMagneticButtons);

            // Fallback: Load Livewire from CDN if local file fails
            setTimeout(function() {
                if (typeof window.Livewire === 'undefined') {
                    console.warn('Livewire not loaded from local assets, loading from CDN...');
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/livewire@3/dist/livewire.min.js';
                    script.onerror = function() {
                        console.error('Failed to load Livewire from CDN');
                    };
                    script.onload = function() {
                        console.log('Livewire loaded from CDN successfully');
                        if (window.Livewire && typeof window.Livewire.start === 'function') {
                            window.Livewire.start();
                        }
                    };
                    document.head.appendChild(script);
                }
            }, 1000);
        </script>

        <style>
            @keyframes viewFadeIn {
                from { opacity: 0; transform: translateY(8px) scale(0.995); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }
            .animate-view-fade-in {
                animation: viewFadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .magnetic-btn {
                transition: transform 0.2s cubic-bezier(0.33, 1, 0.68, 1);
            }
            
            @keyframes progress-ring {
                0% { stroke-dashoffset: 377; }
                50% { stroke-dashoffset: 180; }
                100% { stroke-dashoffset: 0; }
            }
            @keyframes pulse-subtle {
                0%, 100% { transform: scale(1); opacity: 1; }
                50% { transform: scale(0.95); opacity: 0.9; }
            }
            .animate-progress-ring {
                animation: progress-ring 2.3s cubic-bezier(0.65, 0, 0.35, 1) infinite;
            }
            .animate-pulse-subtle {
                animation: pulse-subtle 3s ease-in-out infinite;
            }
            #page-loader.opacity-0 {
                pointer-events: none;
            }
        </style>
        @stack('modals')
    </body>
</html>