<x-admin-layout>
<div class="admin-operations" x-data="{ activeTab: @js(in_array(request('tab'), ['general','integration','database']) ? request('tab') : 'general') }">
    <header class="ops-page-heading"><div><h1>System settings</h1><p>Manage platform identity, integrations, and storage in one place.</p></div></header>
    <div class="ops-tabs" role="tablist" aria-label="Settings sections">
        @foreach(['general'=>'General','integration'=>'Integrations','database'=>'Database & storage'] as $key=>$label)
        <button type="button" role="tab" id="settings-tab-{{ $key }}" aria-controls="settings-panel-{{ $key }}" :aria-selected="activeTab === '{{ $key }}'" :class="activeTab === '{{ $key }}' && 'is-active'" @click="activeTab = '{{ $key }}'; window.history.replaceState(null, '', '?tab={{ $key }}')">{{ $label }}</button>
        @endforeach
    </div>
    <section role="tabpanel" id="settings-panel-general" aria-labelledby="settings-tab-general" x-show="activeTab === 'general'" x-transition.opacity x-cloak>@livewire('admin.global-settings')</section>
    <section role="tabpanel" id="settings-panel-integration" aria-labelledby="settings-tab-integration" x-show="activeTab === 'integration'" x-transition.opacity x-cloak>@livewire('admin.integration-hub')</section>
    <section role="tabpanel" id="settings-panel-database" aria-labelledby="settings-tab-database" x-show="activeTab === 'database'" x-transition.opacity x-cloak>@livewire('admin.database-maintenance')</section>
</div>
</x-admin-layout>
