<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary-800 leading-tight">
            {{ __('Admin - Monetization Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('admin.monetization-control')
        </div>
    </div>
</x-app-layout>
