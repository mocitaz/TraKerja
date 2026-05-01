@props(['active', 'icon' => ''])

@php
$classes = ($active ?? false)
            ? 'group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors bg-indigo-50 text-indigo-700'
            : 'group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:bg-gray-50 hover:text-gray-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
    @if($icon)
        <svg class="mr-3 flex-shrink-0 h-5 w-5 {{ ($active ?? false) ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
        </svg>
    @endif
    <span 
        x-show="$store.sidebar.open" 
        x-transition:enter="transition ease-out duration-200" 
        x-transition:enter-start="opacity-0 translate-x-[-10px]" 
        x-transition:enter-end="opacity-100 translate-x-0"
        class="truncate"
    >
        {{ $slot }}
    </span>
</a>
