<x-app-layout>
    <div class="workspace-page tracker-page" x-data="{ view: 'table', init() { try { this.view = localStorage.getItem('trakerja.tracker.view') === 'kanban' ? 'kanban' : 'table'; } catch (_) {} }, setView(value) { this.view = value; try { localStorage.setItem('trakerja.tracker.view', value); } catch (_) {} } }">
        <div class="workspace-page__container">
            <header class="workspace-page__header">
                <div><h1>{{ __('Applications') }}</h1><p>{{ __('A clear view of every opportunity and what comes next.') }}</p></div>
                <button type="button" onclick="openJobModal()" class="workspace-button workspace-button--primary"><i class="ph ph-plus" aria-hidden="true"></i>{{ __('Add application') }}</button>
            </header>
            <div class="tracker-viewbar">
                <div class="workspace-switcher" aria-label="{{ __('Application views') }}">
                    <button type="button" @click="setView('table')" id="table-view-btn" :class="{ 'is-active': view === 'table' }" :aria-pressed="view === 'table'" aria-controls="list-view-container"><i class="ph ph-list" aria-hidden="true"></i> {{ __('List') }}</button>
                    <button type="button" @click="setView('kanban')" id="kanban-view-btn" :class="{ 'is-active': view === 'kanban' }" :aria-pressed="view === 'kanban'" aria-controls="kanban-view-container"><i class="ph ph-columns" aria-hidden="true"></i> {{ __('Board') }}</button>
                </div>
            </div>
            <div id="list-view-container" x-show="view === 'table'">@livewire('job-table-list')</div>
            <div id="kanban-view-container" x-show="view === 'kanban'" x-cloak>@livewire('job-kanban-board')</div>
        </div>
    </div>

    @push('modals')
    <x-job-application-modal />
    @endpush

    @push('styles')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e4e4e7;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d4d4d8;
        }
    </style>
    @endpush


</x-app-layout>
