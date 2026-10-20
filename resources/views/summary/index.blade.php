<x-app-layout>
    @php
        $hasAccess = !\App\Models\Setting::isMonetizationEnabled() || auth()->user()->isPremium();
        $statuses = $summary['statuses'];
        $periodLabel = match ($timeFilter) { 'weekly' => 'Last 7 days', 'monthly' => 'Last 30 days', default => 'All time' };
        $acceptedShare = round($statuses['Accepted'] / max(1, $applicationsCount) * 100, 1);
        $statusRows = [
            ['name' => __('In progress'), 'count' => $statuses['On Process'], 'tone' => 'purple'],
            ['name' => __('Accepted'), 'count' => $statuses['Accepted'], 'tone' => 'green'],
            ['name' => __('Rejected'), 'count' => $statuses['Rejected'], 'tone' => 'clay'],
        ];
        $statusRows = array_map(fn ($row) => [...$row, 'share' => round($row['count'] / max(1, $applicationsCount) * 100, 1)], $statusRows);
    @endphp
    <div class="workspace-page insights-page">
        <div class="insights-frame workspace-page-frame">
        @if(!$hasAccess)
            <section class="insights-access" aria-labelledby="insights-access-title">
                <span class="insights-eyebrow">{{ __('Summary') }}</span>
                <h1 id="insights-access-title">{{ __('A clearer view of your job search.') }}</h1>
                <p>{{ __('Explore your activity, application sources, locations and recorded outcomes in one report.') }}</p>
                <div><a class="insights-primary" href="{{ route('payment.premium') }}">{{ __('Explore Premium') }}</a><a class="insights-link" href="{{ route('tracker') }}" wire:navigate>{{ __('Back to applications') }}</a></div>
            </section>
        @else
            <header class="workspace-page__header insights-header">
                <div><h1>{{ __('Summary') }}</h1><p>{{ __('A closer look at your activity, opportunities and next steps.') }}</p></div>
                <form method="GET" action="{{ route('summary') }}" class="insights-period">
                    <div class="workspace-switcher" role="group" aria-label="{{ __('Report period') }}">
                        @foreach(['all' => 'All time', 'monthly' => 'Last 30 days', 'weekly' => 'Last 7 days'] as $value => $label)
                            <button type="submit" name="timeFilter" value="{{ $value }}" @class(['is-active' => $timeFilter === $value]) aria-pressed="{{ $timeFilter === $value ? 'true' : 'false' }}">{{ __($label) }}</button>
                        @endforeach
                    </div>
                </form>
            </header>
            <div class="insights-section-label"><h2>{{ __('Activity & progress') }}</h2><span>{{ __('Your pace, and where things stand') }}</span></div>
            <div class="insights-top-grid">
                @include('summary.report.activity')
                <section class="insights-panel insights-outcomes" aria-labelledby="insights-outcome-title" x-data="{ selected: null, hovered: null, dot: null, rows: @js($statusRows), get inspected() { return this.hovered ?? this.selected ?? 1 } }" @keydown.escape="selected = null; hovered = null; dot = null">
                    <header class="insights-panel-heading"><div><h2 id="insights-outcome-title">{{ __('Where things stand') }}</h2><p>{{ __('Current recorded outcomes') }}</p></div></header>
                    <div class="insights-outcome-total" aria-live="polite" aria-atomic="true"><strong><span x-text="rows[inspected].share">{{ $acceptedShare }}</span><small>%</small></strong><div class="insights-outcome-caption"><span x-text="rows[inspected].name">{{ __('Accepted') }}</span><small><b x-text="rows[inspected].count">{{ $statuses['Accepted'] }}</b> / {{ number_format($applicationsCount) }} {{ __('applications') }}</small></div></div>
                    @php
                        $progressDots = $applicationsCount ? round($statuses['On Process'] / $applicationsCount * 100) : 0;
                        $acceptedDots = $applicationsCount ? min(100 - $progressDots, round($statuses['Accepted'] / $applicationsCount * 100)) : 0;
                    @endphp
                    <div class="insights-waffle" role="img" aria-label="{{ __(':value1 in progress, :value2 accepted, :value3 rejected', ['value1' => $statuses['On Process'], 'value2' => $statuses['Accepted'], 'value3' => $statuses['Rejected']]) }}" @mouseleave="hovered = null; dot = null">
                        @for($dot = 0; $dot < 100; $dot++)
                            @php($dotStatus = $dot < $progressDots ? 0 : ($dot < $progressDots + $acceptedDots ? 1 : 2))
                            <i aria-hidden="true" @class(['insights-waffle-dot', 'is-empty' => !$applicationsCount, 'insights-fill-'.$statusRows[$dotStatus]['tone'] => $applicationsCount]) @if($applicationsCount) @mouseenter="hovered = {{ $dotStatus }}; dot = {{ $dot }}" @click="selected = selected === {{ $dotStatus }} ? null : {{ $dotStatus }}" @endif :class="{ 'is-muted': (hovered ?? selected) !== null && (hovered ?? selected) !== {{ $dotStatus }}, 'is-pointed': dot === {{ $dot }} }"></i>
                        @endfor
                        <div class="insights-waffle-tooltip" x-cloak x-show="dot !== null" :style="{ left: Math.max(30, Math.min(70, ((dot ?? 0) % 10 + .5) * 10)) + '%', top: Math.floor((dot ?? 0) / 10) * 10 + '%' }" aria-hidden="true"><strong x-text="rows[inspected].name"></strong><span><b x-text="rows[inspected].count"></b> {{ __('applications') }} · <b x-text="rows[inspected].share + '%'"></b></span></div>
                    </div>
                    <div class="insights-outcome-legend">
                        @foreach($statusRows as $index => $status)<button type="button" @mouseenter="hovered = {{ $index }}" @mouseleave="hovered = null" @focus="hovered = {{ $index }}" @blur="hovered = null" @click="selected = selected === {{ $index }} ? null : {{ $index }}" :class="{ 'is-inspected': (hovered ?? selected) === {{ $index }} }" :aria-pressed="selected === {{ $index }}"><span><i class="insights-fill-{{ $status['tone'] }}" aria-hidden="true"></i>{{ $status['name'] }}</span><strong>{{ $status['count'] }}<small>{{ $status['share'] }}%</small></strong></button>@endforeach
                    </div>
                    <p class="insights-note">{{ __('100 dots represent 100%. Dot counts are rounded; figures show exact totals.') }}</p>
                </section>
            </div>

            @include('summary.report.stages')

            <div class="insights-section-label"><h2>{{ __('Where opportunities come from') }}</h2><span>{{ __('Sources and locations') }}</span></div>
            <div class="insights-opportunities-grid">
                @include('summary.report.sources')
                @include('summary.report.geography')
            </div>

            <div class="insights-section-label"><h2>{{ __('The shape of your search') }}</h2><span>{{ __('Roles, work types and companies') }}</span></div>
            <div class="insights-profile-grid">
                @include('summary.report.roles')
                @include('summary.report.employment')
                @include('summary.report.companies')
            </div>
            <div class="insights-closing"><p>{{ __('Keep your stages and outcomes up to date for a more useful summary.') }}</p><a href="{{ route('tracker') }}" wire:navigate class="insights-primary">{{ __('Open applications') }}<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></div>
        @endif
        </div>
    </div>
</x-app-layout>
