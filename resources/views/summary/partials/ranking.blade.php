@php($maximum = max(1, $rows->max('total') ?? 1))
<div class="summary-ranking" x-data="{ expanded: false }">
    @forelse($rows as $row)
        <div class="summary-ranking-row" @if($loop->index >= 6) x-show="expanded" x-cloak @endif>
            <div class="summary-ranking-identity">@if($type === 'sources')<x-platform-brand :name="$row['name']" />@else<strong>{{ $row['name'] }}</strong>@endif<p>{{ __(':value1 in interview stages · :value2 accepted', ['value1' => ($row['interviews']), 'value2' => ($row['accepted'])]) }}</p></div>
            <div class="summary-ranking-volume"><span class="summary-mini-track" aria-hidden="true"><span style="width: {{ $row['total'] / $maximum * 100 }}%"></span></span><strong>{{ $row['total'] }}</strong><small>{{ round($row['total'] / max(1, $applicationsCount) * 100, 1) }}%</small></div>
        </div>
    @empty
        <div class="summary-analysis-empty"><h3>{{ __('No records in this period.') }}</h3><p>{{ __('Your saved applications will populate this analysis.') }}</p></div>
    @endforelse
    @if($rows->count() > 6)<button type="button" @click="expanded = !expanded" :aria-expanded="expanded" class="summary-show-more"><span x-text="expanded ? 'Show fewer' : 'Show all {{ $rows->count() }}'">{{ __('Show all :value1', ['value1' => ($rows->count())]) }}</span><i class="ph ph-caret-down" aria-hidden="true" :class="{ 'is-expanded': expanded }"></i></button>@endif
</div>
