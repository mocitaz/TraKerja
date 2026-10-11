<x-admin-layout>
    <div class="admin-operations admin-settings-workspace" x-data="{ activeTab: @js(in_array(request('tab'), ['general','integration','database']) ? request('tab') : 'general') }">

        {{-- ==========================================================================
             PAGE HEADER (Harmonized with User Management & Summary)
             ========================================================================== --}}
        <section class="ast-welcome">
            <div>
                <span class="ast-eyebrow">Platform Configuration</span>
                <h1>System Settings</h1>
                <p>Manage platform identity, external API integrations, and database maintenance in one workspace.</p>
            </div>
        </section>

        {{-- ==========================================================================
             SETTINGS TABS SWITCHER
             ========================================================================== --}}
        <nav class="ast-tabs" role="tablist" aria-label="Settings sections">
            @foreach(['general' => 'General', 'integration' => 'Integrations', 'database' => 'Database & storage'] as $key => $label)
                <button type="button" 
                        role="tab" 
                        id="settings-tab-{{ $key }}" 
                        aria-controls="settings-panel-{{ $key }}" 
                        :aria-selected="activeTab === '{{ $key }}'" 
                        class="ast-tab"
                        :class="activeTab === '{{ $key }}' ? 'is-active' : ''" 
                        @click="activeTab = '{{ $key }}'; window.history.replaceState(null, '', '?tab={{ $key }}')">
                    @switch($key)
                        @case('general')
                            <i class="ph ph-sliders" aria-hidden="true"></i>
                            @break
                        @case('integration')
                            <i class="ph ph-plugs-connected" aria-hidden="true"></i>
                            @break
                        @case('database')
                            <i class="ph ph-database" aria-hidden="true"></i>
                            @break
                    @endswitch
                    <span>{{ $label }}</span>
                </button>
            @endforeach
        </nav>

        {{-- ==========================================================================
             TAB PANELS
             ========================================================================== --}}
        <section role="tabpanel" id="settings-panel-general" aria-labelledby="settings-tab-general" x-show="activeTab === 'general'" x-transition.opacity x-cloak>
            @livewire('admin.global-settings')
        </section>

        <section role="tabpanel" id="settings-panel-integration" aria-labelledby="settings-tab-integration" x-show="activeTab === 'integration'" x-transition.opacity x-cloak>
            @livewire('admin.integration-hub')
        </section>

        <section role="tabpanel" id="settings-panel-database" aria-labelledby="settings-tab-database" x-show="activeTab === 'database'" x-transition.opacity x-cloak>
            @livewire('admin.database-maintenance')
        </section>

    </div>
</x-admin-layout>
