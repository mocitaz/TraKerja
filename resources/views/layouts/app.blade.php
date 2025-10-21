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
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50 flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <x-footer />
        </div>
        
        @livewireScripts
        
        <script>
        // Close notification panel on page navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for navigation events
            const links = document.querySelectorAll('a[href]');
            links.forEach(link => {
                link.addEventListener('click', function() {
                    // Close notification panel if open
                    if (window.Livewire) {
                        const notificationComponent = window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                        if (notificationComponent && notificationComponent.get('showNotifications')) {
                            notificationComponent.call('closePanel');
                        }
                    }
                });
            });
        });
        </script>
    </body>
</html>
