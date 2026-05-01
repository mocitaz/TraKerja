<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Career <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-purple-600 to-[#4e71c5]">Goals</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Consistency is the bridge between goals and accomplishment</p>
        </div>
    </x-slot>

    <livewire:goals-cadence-manager />
</x-app-layout>
