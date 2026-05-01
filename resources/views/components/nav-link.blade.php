@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all'
            : 'flex items-center px-4 py-2 text-slate-500 hover:text-indigo-600 hover:bg-slate-50 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
    {{ $slot }}
</a>
