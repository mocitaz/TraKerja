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
                        <x-footer />
                    </div>
                @endunless
            </div>
        </div>

        @livewireScripts
        
        <!-- Global Motion Engine (Magnetic Buttons & Transitions) -->
        <script>
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

            // Auto-close sidebar on Livewire page navigation
            document.addEventListener('livewire:navigating', () => {
                if (window.Alpine && Alpine.store('sidebar')) {
                    Alpine.store('sidebar').close();
                }
            });

            document.addEventListener('DOMContentLoaded', initMagneticButtons);
            document.addEventListener('livewire:navigated', initMagneticButtons);


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
        </style>
        @stack('modals')
    </body>
</html>