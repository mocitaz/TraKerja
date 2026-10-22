<x-admin-layout>
@php
    $initial = ['integration' => 'integrations', 'database' => 'maintenance'][is_string(request('tab')) ? request('tab') : ''] ?? 'general';
    $sections = ['general' => ['General', 'Identity & saved defaults', 'sliders-horizontal'], 'integrations' => ['Integrations', 'Services & custom connections', 'plugs-connected'], 'maintenance' => ['Maintenance', 'Access, backup & storage', 'wrench']];
@endphp
<div class="as-workspace" x-data="adminSettingsWorkspace(@js($initial))">
    <header class="am-page-heading"><div><h1>{{ __('Settings') }}</h1><p>{{ __('One place for platform settings, integrations, and maintenance.') }}</p></div><span class="as-save-note"><i class="ph ph-check-circle" aria-hidden="true"></i>{{ __('Each form saves independently') }}</span></header>
    <div class="as-layout">
        <nav class="as-navigation" aria-label="{{ __('Settings sections') }}">
            <p>{{ __('Platform controls') }}</p>
            @foreach($sections as $key => [$label, $description, $icon])<a href="#{{ $key }}" @click.prevent="navigate('{{ $key }}')" :aria-current="section === '{{ $key }}' ? 'location' : null"><i class="ph ph-{{ $icon }}" aria-hidden="true"></i><span><strong>{{ __($label) }}</strong><small>{{ __($description) }}</small></span><i class="ph ph-caret-right as-nav-caret" aria-hidden="true"></i></a>@endforeach
            <div class="as-navigation-note"><i class="ph ph-info" aria-hidden="true"></i><span>{{ __('Review a section, make your changes, then save its form.') }}</span></div>
        </nav>
        <div class="as-sections">
            <section id="general" class="as-section" aria-labelledby="settings-general-heading"><header class="as-section-heading"><div><h2 tabindex="-1" id="settings-general-heading">{{ __('General') }}</h2><p>{{ __('Platform identity and saved quota defaults.') }}</p></div><span class="as-section-number">01</span></header>@livewire('admin.global-settings')</section>
            <section id="integrations" class="as-section" aria-labelledby="settings-integrations-heading"><header class="as-section-heading"><div><h2 tabindex="-1" id="settings-integrations-heading">{{ __('Integrations') }}</h2><p>{{ __('Review service configuration and manage custom connection settings.') }}</p></div><span class="as-section-number">02</span></header>@livewire('admin.integration-hub')</section>
            <section id="maintenance" class="as-section" aria-labelledby="settings-maintenance-heading"><header class="as-section-heading"><div><h2 tabindex="-1" id="settings-maintenance-heading">{{ __('Maintenance') }}</h2><p>{{ __('Control member access, export your database, and review unused logo files.') }}</p></div><span class="as-section-number">03</span></header>@livewire('admin.database-maintenance')</section>
        </div>
    </div>
</div>
</x-admin-layout>
