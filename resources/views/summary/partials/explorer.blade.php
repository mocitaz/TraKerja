@php
    $palette = ['#67847a', '#7b94ac', '#9b89af', '#bb9982', '#96aba2', '#a7b7c6', '#c2b6cf', '#cec9c3'];
    $items = $rows->values()->map(fn ($row, $index) => $row + ['share' => round($row['total'] / max(1, $applicationsCount) * 100, 1), 'color' => $palette[$index % 7], 'index' => $index])->all();
    $visibleItems = array_slice($items, 0, 7);
    if (count($items) > 7) {
        $remaining = collect(array_slice($items, 7));
        $other = ['name' => 'Other '.$label, 'total' => $remaining->sum('total'), 'interviews' => $remaining->sum('interviews'), 'accepted' => $remaining->sum('accepted'), 'color' => $palette[7], 'index' => count($items)];
        $other['share'] = round($other['total'] / max(1, $applicationsCount) * 100, 1);
        $visibleItems[] = $other;
        $items[] = $other;
    }
    $maximum = max(1, collect($visibleItems)->max('total') ?? 1);
    $map = $type === 'locations' ? \App\Support\SummaryLocationMap::fromRows($rows) : null;
    $mapDefaultIndex = $map ? collect($map['mapped'])->sortByDesc('total')->keys()->first() : null;
    $mapDefaultView = $mapDefaultIndex !== null ? $map['mapped'][$mapDefaultIndex]['view'] : '0 0 960 400';
    $visualTitle = match($type) { 'sources' => 'Where you apply', 'roles' => 'Roles you pursue', 'locations' => 'Your location mix', 'companies' => 'Companies on your radar', default => 'How you want to work' };
@endphp
@if($rows->isEmpty())
    <div class="summary-analysis-empty"><h3>{{ __('No records in this period.') }}</h3><p>{{ __('Your saved applications will populate this analysis.') }}</p></div>
@else
    <div class="summary-explorer" @resize.window.debounce.150ms="mapLabelScale = window.innerWidth < 640 ? .045 : .021" x-data="{ mapLabelScale: window.innerWidth < 640 ? .045 : .021, hovered: null, selected: null, items: @js($items), mapView: @js($mapDefaultView), defaultMapView: @js($mapDefaultView), mapFocus: @js($mapDefaultIndex), mapSelected: @js($mapDefaultIndex), selectCity(i) { this.mapFocus = i; this.mapSelected = i; this.mapView = this.places[i].view; }, places: @js($map['mapped'] ?? []) }">
        <div class="summary-explorer-visual summary-explorer-visual--{{ $type }}">
            <header><h3>{{ $visualTitle }}</h3><span>{{ $rows->count() }} {{ strtolower($label) }}</span></header>
            @if(in_array($type, ['sources', 'employment']))
                <div class="summary-explorer-donut">
                    <svg viewBox="0 0 200 200" role="group" aria-label="{{ __($label) }} distribution">
                        <circle cx="100" cy="100" r="76" fill="none" stroke="#e7e3ea" stroke-width="20" />
                        @php($offset = 0)
                        @foreach($visibleItems as $item)
                            @php($segment = $item['total'] / max(1, $applicationsCount) * 100)
                            <g tabindex="0" role="img" aria-label="{{ __(':value1: :value2 applications, :value3 percent', ['value1' => ($item['name']), 'value2' => ($item['total']), 'value3' => ($item['share'])]) }}" @mouseenter="hovered = {{ $item['index'] }}" @mouseleave="hovered = null" @focus="hovered = {{ $item['index'] }}" @blur="hovered = null">
                                <circle cx="100" cy="100" r="76" pathLength="100" fill="none" stroke="{{ $item['color'] }}" stroke-width="20" stroke-dasharray="{{ $segment }} {{ 100 - $segment }}" stroke-dashoffset="{{ -$offset }}" transform="rotate(-90 100 100)" class="explorer-ring" :class="{ 'is-selected': (hovered ?? selected) === {{ $item['index'] }}, 'is-muted': (hovered ?? selected) !== null && (hovered ?? selected) !== {{ $item['index'] }} }" />
                            </g>
                            @php($offset += $segment)
                        @endforeach
                    </svg>
                    <div aria-live="polite">
                        @if($type === 'sources')
                            @foreach($visibleItems as $item)
                                <span class="summary-donut-platform" x-show="(hovered ?? selected) === {{ $item['index'] }}" x-cloak><x-platform-brand :name="$item['name']" :show-name="false" /></span>
                            @endforeach
                        @endif
                        <strong x-text="(hovered ?? selected) === null ? @js(__('{{ $applicationsCount }}')) : items[hovered ?? selected].share + '%'">{{ $applicationsCount }}</strong><span x-text="(hovered ?? selected) === null ? @js(__('Applications')) : items[hovered ?? selected].name">{{ __('Applications') }}</span><small x-show="(hovered ?? selected) !== null" x-cloak x-text="(hovered ?? selected) === null ? '' : items[hovered ?? selected].total + @js(__(' applications'))"></small></div>
                </div>
                <div class="summary-category-list" role="group" aria-label="{{ __($label) }} breakdown">
                    @foreach($visibleItems as $item)
                        <button type="button" @click="selected = selected === {{ $item['index'] }} ? null : {{ $item['index'] }}" @mouseenter="hovered = {{ $item['index'] }}" @mouseleave="hovered = null" @focus="hovered = {{ $item['index'] }}" @blur="hovered = null" :aria-pressed="selected === {{ $item['index'] }}" :class="{ 'is-active': (hovered ?? selected) === {{ $item['index'] }} }">
                            <i style="background: {{ $item['color'] }}" aria-hidden="true"></i><span>{{ $item['name'] }}</span><strong>{{ $item['total'] }}</strong><small>{{ $item['share'] }}%</small>
                        </button>
                    @endforeach
                </div>
            @elseif($type === 'locations')
                <div class="summary-map-toolbar">
                    <span>{{ __(':value1 applications · :value2 mapped locations', ['value1' => (collect($map['mapped'])->sum('total')), 'value2' => (count($map['mapped']))]) }}</span>
                    <div role="group" aria-label="{{ __('Map zoom') }}">
                        @if($mapDefaultIndex !== null)<button type="button" @click="selectCity({{ $mapDefaultIndex }})" :aria-pressed="mapView === defaultMapView">{{ __('Top location') }}</button>@endif
                        <button type="button" @click="mapSelected = null; mapFocus = null; mapView = '0 0 960 400'" :aria-pressed="mapView === '0 0 960 400'">{{ __('Indonesia') }}</button>
                        <button type="button" @click="mapSelected = null; mapFocus = null; mapView = '235 235 245 105'" :aria-pressed="mapView === '235 235 245 105'">{{ __('Java & Bali') }}</button>
                    </div>
                </div>
                <div class="summary-indonesia-map summary-region-atlas">
                    <svg viewBox="{{ $mapDefaultView }}" x-bind:view-box.camel="mapView" role="group" aria-label="{{ __('Application volume by city boundaries in Indonesia') }}">
                        @include('summary.partials.indonesia-map-path')
                        <g aria-hidden="true">@foreach($map['districts'] as $district)<path class="summary-map-district" fill-rule="evenodd" d="{{ $district['path'] }}" />@endforeach</g>
                        @foreach($map['mapped'] as $index => $place)
                            <g class="summary-map-region" tabindex="0" role="button" aria-label="{{ __(':value1: :value2 applications, :value3 percent', ['value1' => ($place['name']), 'value2' => ($place['total']), 'value3' => ($place['share'])]) }}" @mouseenter="mapFocus = {{ $index }}" @mouseleave="mapFocus = mapSelected" @focus="mapFocus = {{ $index }}" @blur="mapFocus = mapSelected" @click="selectCity({{ $index }})" @keydown.enter.prevent="selectCity({{ $index }})" @keydown.space.prevent="selectCity({{ $index }})" :aria-pressed="mapSelected === {{ $index }}" :class="{ 'is-selected': mapSelected === {{ $index }}, 'is-hovered': mapFocus === {{ $index }} }">
                                <path class="summary-map-city" fill="{{ $place['color'] }}" fill-rule="evenodd" d="{{ $place['path'] }}"><title>{{ __(':value1 · :value2 applications · :value3%', ['value1' => (implode(' / ', $place['labels'])), 'value2' => ($place['total']), 'value3' => ($place['share'])]) }}</title></path>
                                <text class="summary-map-region-label" x="{{ $place['labelX'] }}" y="{{ $place['labelY'] }}" text-anchor="middle" dominant-baseline="middle" :font-size="Number(mapView.split(' ')[2]) * mapLabelScale" :stroke-width="Number(mapView.split(' ')[2]) * .003" x-cloak x-show="mapSelected === {{ $index }}">{{ $place['name'] }}</text>
                            </g>
                        @endforeach
                    </svg>
                    <div class="summary-map-inset" aria-hidden="true"><span>{{ __('INDONESIA') }}</span><svg viewBox="0 0 960 400">@include('summary.partials.indonesia-map-path')<rect :x="Number(mapView.split(' ')[0])" :y="Number(mapView.split(' ')[1])" :width="Number(mapView.split(' ')[2])" :height="Number(mapView.split(' ')[3])" /></svg></div>
                    <div class="summary-atlas-caption"><span>{{ __('REGIONAL VIEW') }}</span><span>{{ __('City & district boundaries') }}</span></div>
                </div>
                <div class="summary-atlas-selection" aria-live="polite">
                    <div><span>{{ __('Selected location') }}</span><strong x-text="mapFocus === null ? '{{ count($map['mapped']) ? __('Indonesia overview') : __('No geographic areas available') }}' : places[mapFocus].labels.join(' / ')">{{ $mapDefaultIndex !== null ? implode(' / ', $map['mapped'][$mapDefaultIndex]['labels']) : __('No geographic areas available') }}</strong></div>
                    <p x-text="mapFocus === null ? @js(__('Choose a location below to inspect its area.')) : places[mapFocus].total + @js(__(' applications · ')) + places[mapFocus].share + @js(__('% of total · ')) + places[mapFocus].accepted + @js(__(' accepted'))"></p>
                </div>
                <div class="summary-map-scale"><span>{{ __('Application volume') }}</span><i aria-hidden="true"></i><span>{{ __('Low → :value1 highest', ['value1' => ($map['maximum'])]) }}</span><button type="button" @click="mapSelected = null; mapFocus = null; mapView = '0 0 960 400'">{{ __('Show all Indonesia') }}</button></div>
                <div class="summary-map-places summary-region-list">
                    @foreach($map['mapped'] as $index => $place)
                        <button type="button" @click="selectCity({{ $index }})" :aria-pressed="mapSelected === {{ $index }}"><span>{{ implode(' / ', $place['labels']) }}</span><strong>{{ $place['total'] }}</strong><small>{{ $place['share'] }}%</small><i aria-hidden="true"><span style="width: {{ $place['total'] / $map['maximum'] * 100 }}%"></span></i></button>
                    @endforeach
                </div>
                @if(count($map['unmapped']))<div class="summary-map-unmapped"><span>{{ __('Outside the map') }}</span>@foreach($map['unmapped'] as $place)<span>{{ $place['name'] }} <strong>{{ $place['total'] }}</strong></span>@endforeach<p>{{ __('Remote, unrecognized locations, or locations without available city boundaries. Included in your totals.') }}</p></div>@endif
                <p class="summary-explorer-note">{{ __('Color shows application volume, not geographic area or conversion. Jakarta combines five mainland cities; Bali is represented by Denpasar. Context boundaries are simplified; highlighted city boundaries retain their source detail.') }} <a href="https://www.naturalearthdata.com/about/terms-of-use/" target="_blank" rel="noopener">{{ __('Outline: Natural Earth') }}</a> · <a href="https://www.geoboundaries.org/api/current/gbOpen/IDN/ADM2/" target="_blank" rel="noopener">{{ __('Boundaries: geoBoundaries / BPS (CC BY 3.0 IGO)') }}</a> · <a href="https://www.geonames.org/about.html" target="_blank" rel="noopener">{{ __('Cities: GeoNames') }}</a></p>
            @else
                <div class="summary-explorer-bars">
                    @foreach($visibleItems as $item)
                        <button type="button" @mouseenter="hovered = {{ $item['index'] }}" @mouseleave="hovered = null" @focus="hovered = {{ $item['index'] }}" @blur="hovered = null" :class="{ 'is-selected': (hovered ?? selected) === {{ $item['index'] }} }" style="--explorer-color: {{ $item['color'] }}">
                            <span>{{ $item['name'] }}</span><strong>{{ $item['total'] }}</strong><small class="summary-bar-context">{{ __(':value1% of applications · :value2 interview stages · :value3 accepted', ['value1' => ($item['share']), 'value2' => ($item['interviews']), 'value3' => ($item['accepted'])]) }}</small><i aria-hidden="true"><span style="width: {{ $item['total'] / max($maximum, $item['total']) * 100 }}%"></span></i>
                        </button>
                    @endforeach
                </div>
            @endif
            @if($type !== 'locations')
                <p class="summary-explorer-note">{{ $rows->count() > 7 ? __('Top 7 categories; remaining records grouped as Other.') : __('Select a category to explore its share and volume.') }}</p>
            @endif
        </div>
        <details class="summary-chart-data"><summary>{{ __('View chart data · All :value1', ['value1' => (strtolower($label))]) }}</summary><div><table><caption class="sr-only">{{ __('All :value1', ['value1' => (strtolower($label))]) }}</caption><thead><tr><th>{{ __($label) }}</th><th>{{ __('Applications') }}</th><th>{{ __('Share') }}</th><th>{{ __('Interview stages') }}</th><th>{{ __('Accepted') }}</th></tr></thead><tbody>@foreach($rows as $index => $row)<tr><th scope="row">{{ $row['name'] }}</th><td>{{ $row['total'] }}</td><td>{{ $items[$index]['share'] }}%</td><td>{{ $row['interviews'] }}</td><td>{{ $row['accepted'] }}</td></tr>@endforeach</tbody></table></div></details>
    </div>
@endif
