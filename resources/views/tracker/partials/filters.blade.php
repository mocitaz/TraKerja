@php($activeFilterCount = (int) filled($platformFilter) + (int) filled($recruitmentStageFilter) + (int) ($mode === 'table' && filled($statusFilter)) + (int) $showArchived)
<div class="tracker-filters" x-data="{ filtersExpanded: @js($activeFilterCount > 0) }" :class="{ 'is-mobile-expanded': filtersExpanded }">
    <div class="tracker-search"><i class="ph ph-magnifying-glass" aria-hidden="true"></i><input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search roles, companies, locations…') }}" aria-label="{{ __('Search applications') }}"></div>
    <button type="button" class="tracker-mobile-filter-toggle" @click="filtersExpanded = !filtersExpanded" :aria-expanded="filtersExpanded" aria-controls="tracker-filters-{{ $mode }}"><i class="ph ph-sliders-horizontal" aria-hidden="true"></i>Filters @if($activeFilterCount)<span>{{ $activeFilterCount }}</span>@endif</button>
    <div class="tracker-filter-controls" id="tracker-filters-{{ $mode }}">
        @if($mode === 'table')<select wire:model.live="statusFilter" aria-label="{{ __('Filter by status') }}"><option value="">{{ __('All statuses') }}</option>@foreach($statusOptions as $status)<option value="{{ $status }}">{{ $status }}</option>@endforeach</select>@endif
        <select wire:model.live="platformFilter" aria-label="{{ __('Filter by source') }}"><option value="">{{ __('All sources') }}</option>@foreach($platformOptions as $platform)<option value="{{ $platform }}">{{ $platform }}</option>@endforeach</select>
        <select wire:model.live="recruitmentStageFilter" aria-label="{{ __('Filter by recruitment stage') }}"><option value="">{{ __('All stages') }}</option>@foreach($recruitmentStageOptions as $stage)<option value="{{ $stage }}">{{ $stage }}</option>@endforeach</select>
        <button type="button" wire:click="toggleArchived" class="tracker-archive {{ $showArchived ? 'is-active' : '' }}" aria-pressed="{{ $showArchived ? 'true' : 'false' }}"><i class="ph ph-archive" aria-hidden="true"></i>{{ $showArchived ? __('Back to active') : __('Archive') }}<span>{{ $archivedCount }}</span></button>
    </div>
    <div class="tracker-archive-note"><span>{{ __('Auto-archive :value1 · Rejected / Not Processed after 14 days without changes.', ['value1' => (auth()->user()->auto_archive_rejected ? 'on' : 'off')]) }}</span><a href="{{ route('profile.edit') }}" wire:navigate>{{ __('Manage preference') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
    @if($search || $platformFilter || $recruitmentStageFilter || ($mode === 'table' && $statusFilter))
        <div class="tracker-filter-summary"><span>{{ __('Filters applied') }}</span><button type="button" wire:click="clearFilters">{{ __('Clear filters') }} <i class="ph ph-x" aria-hidden="true"></i></button></div>
    @endif
</div>
