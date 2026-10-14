<x-app-layout>
    <div class="workspace-page interviews-page"><div class="workspace-page__container"><header class="workspace-page__header"><div><h1>Make every conversation count.</h1><p>Your interviews and selection rounds, organised around your next step.</p></div><a href="{{ route('tracker') }}" wire:navigate class="workspace-button workspace-button--quiet">Manage applications <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></header><livewire:interview-calendar /></div></div>
</x-app-layout>
