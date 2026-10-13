@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
    'label' => null,
    'labelledby' => null
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth] ?? 'sm:max-w-2xl';
$dialogId = 'modal-' . $name;
@endphp

<div
    id="{{ $dialogId }}"
    role="dialog"
    aria-modal="true"
    @if($labelledby) aria-labelledby="{{ $labelledby }}" @else aria-label="{{ $label ?? $name }}" @endif
    tabindex="-1"
    x-data="{ show: @js($show) }"
    x-dialog="show"
    x-on:open-modal.window="if ($event.detail === @js($name)) show = true"
    x-on:close-modal.window="if ($event.detail === @js($name)) show = false"
    x-on:close.stop="show = false"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 flex items-center justify-center"
    style="display: {{ $show ? 'flex' : 'none' }};"
>
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
    </div>

    <div
        x-show="show"
        class="relative bg-white dark:bg-[#1e1f20] border border-transparent dark:border-white/10 text-slate-800 dark:text-[#f0f4f9] rounded-2xl overflow-hidden shadow-2xl transform transition-all w-full {{ $maxWidth }} sm:mx-auto mx-4"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-on:click.stop
    >
        {{ $slot }}
    </div>
</div>
