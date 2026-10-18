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

        <!-- Anti-FOUC & Theme Initialization Script -->
        <script>
            (function() {
                try {
                    if (!localStorage.getItem('trakerja_theme_force_light_v6')) {
                        localStorage.setItem('theme', 'light');
                        document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
                        localStorage.setItem('trakerja_theme_force_light_v6', '1');
                    }
                    var theme = localStorage.getItem('theme');
                    if (theme === 'dark') {
                        document.documentElement.classList.add('dark');
                        document.cookie = 'theme=dark; path=/; max-age=31536000; SameSite=Lax';
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                        document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
                    }
                } catch (e) {}
            })();
        </script>

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
        @include('partials.locale-config')
</head>
    <body class="font-sans antialiased bg-white dark:bg-[#131314] text-slate-900 dark:text-[#f0f4f9] transition-colors duration-200">
        <div class="gemini-ambient-glow" aria-hidden="true"></div>


        <div class="h-screen flex overflow-hidden">
            <!-- Main Content Area -->
            <div class="workspace-shell flex-1 flex flex-col min-w-0 relative overflow-hidden">
                @include('layouts.navigation', ['header' => $header ?? null])

                <!-- Page Content -->
                <main class="workspace-main flex-1 overflow-y-auto">
                    <div class="workspace-main-content">
                        @include('components.survey-invitation')
                        {{ $slot }}
                    </div>
                    <x-workspace-footer />
                </main>


            </div>
        </div>


        @include('components.toast-notification')
        <x-kawankerja-chat />
        @livewireScripts

        <div id="confirmation-modal" data-dialog-native="closeConfirmModal" role="dialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-message" tabindex="-1" class="account-dialog-overlay hidden" onclick="if (event.target === this) closeConfirmModal()">
            <section class="account-confirm-panel">
                <button type="button" class="account-dialog-close" onclick="closeConfirmModal()" aria-label="{{ __('Close confirmation') }}"><i class="ph ph-x" aria-hidden="true"></i></button>
                <div class="account-confirm-visual" aria-hidden="true"><i class="ph ph-sign-out account-confirm-logout-icon"></i><i class="ph ph-warning account-confirm-action-icon"></i></div>
                <h2 id="confirm-title">{{ __('Confirm action') }}</h2>
                <p id="confirm-message">{{ __('Review this action before continuing.') }}</p>
                <p id="confirm-error" role="alert" hidden></p>
                <div class="account-confirm-actions"><button type="button" id="confirm-cancel" class="account-dialog-button" onclick="closeConfirmModal()">{{ __('Stay here') }}</button><button type="button" id="confirm-button" class="account-dialog-button account-dialog-button--primary">{{ __('Confirm') }}</button></div>
            </section>
        </div>

        <script>
            var activeConfirmCallback = activeConfirmCallback || null;
            var confirmBusy = false;

            function openConfirmModal(title, message, btnText, callback, options = {}) {
                if (confirmBusy) return;
                const modal = document.getElementById('confirmation-modal');
                modal.dataset.variant = options.variant || 'action';
                document.getElementById('confirm-title').innerText = title;
                document.getElementById('confirm-message').innerText = message;
                document.getElementById('confirm-button').innerText = btnText;
                document.getElementById('confirm-cancel').innerText = options.variant === 'logout' ? 'Stay here' : 'Cancel';
                document.getElementById('confirm-error').hidden = true;
                activeConfirmCallback = callback;
                modal.classList.remove('hidden');
            }

            function closeConfirmModal(force = false) {
                if (confirmBusy && !force) return;
                document.getElementById('confirmation-modal').classList.add('hidden');
                activeConfirmCallback = null;
            }

            document.getElementById('confirm-button').onclick = async function() {
                if (confirmBusy || !activeConfirmCallback) return;
                const callback = activeConfirmCallback;
                const modal = document.getElementById('confirmation-modal');
                const buttons = [...modal.querySelectorAll('button')];
                const label = this.innerText;
                confirmBusy = true;
                buttons.forEach(button => { button.disabled = true; });
                this.innerText = modal.dataset.variant === 'logout' ? 'Signing out…' : 'Working…';
                try {
                    await callback();
                    closeConfirmModal(true);
                } catch (error) {
                    const notice = document.getElementById('confirm-error');
                    notice.innerText = 'We couldn’t complete this action. Please try again.';
                    notice.hidden = false;
                } finally {
                    confirmBusy = false;
                    buttons.forEach(button => { button.disabled = false; });
                    this.innerText = label;
                }
            };

            async function confirmLogout(formId = 'logout-form') {
                const submitLogout = async () => {
                    const form = document.getElementById(formId) || document.getElementById('logout-form');
                    if (!form) return;
                    try {
                        await window.prepareTraKerjaLogout?.();
                    } catch (error) {
                        console.error(error);
                    }
                    form.submit();
                };
                if (typeof window.openConfirmModal === 'function') {
                    window.openConfirmModal(
                        'Sign out of TraKerja?',
                        'You’ll need to sign in again to return to your workspace.',
                        'Sign out',
                        submitLogout,
                        { variant: 'logout' }
                    );
                } else if (confirm(@js(__('Are you sure you want to sign out?')))) {
                    await submitLogout();
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
            @include('components.service-worker-registration')

            // Handle Livewire Navigation
            document.addEventListener('livewire:navigated', () => {
                initMagneticButtons();
            });

            document.addEventListener('DOMContentLoaded', initMagneticButtons);

        </script>

        <style>
            .magnetic-btn {
                transition: transform 0.2s cubic-bezier(0.33, 1, 0.68, 1);
            }
        </style>
        @if(session('show_telegram_popup') && !request()->routeIs('community.index'))
            @include('community.popup')
        @endif

        @stack('modals')
    </body>
</html>
