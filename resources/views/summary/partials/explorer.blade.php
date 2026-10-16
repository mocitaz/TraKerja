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
    <div class="summary-analysis-empty"><h3>No records in this period.</h3><p>Your saved applications will populate this analysis.</p></div>
@else
    <div class="summary-explorer" @resize.window.debounce.150ms="mapLabelScale = window.innerWidth < 640 ? .045 : .021" x-data="{ mapLabelScale: window.innerWidth < 640 ? .045 : .021, hovered: null, selected: null, items: @js($items), mapView: @js($mapDefaultView), defaultMapView: @js($mapDefaultView), mapFocus: @js($mapDefaultIndex), mapSelected: @js($mapDefaultIndex), selectCity(i) { this.mapFocus = i; this.mapSelected = i; this.mapView = this.places[i].view; }, places: @js($map['mapped'] ?? []) }">
        <div class="summary-explorer-visual summary-explorer-visual--{{ $type }}">
            <header><h3>{{ $visualTitle }}</h3><span>{{ $rows->count() }} {{ strtolower($label) }}</span></header>
            @if(in_array($type, ['sources', 'employment']))
                <div class="summary-explorer-donut">
                    <svg viewBox="0 0 200 200" role="group" aria-label="{{ $label }} distribution">
                        <circle cx="100" cy="100" r="76" fill="none" stroke="#e7e3ea" stroke-width="20" />
                        @php($offset = 0)
                        @foreach($visibleItems as $item)
                            @php($segment = $item['total'] / max(1, $applicationsCount) * 100)
                            <g tabindex="0" role="img" aria-label="{{ $item['name'] }}: {{ $item['total'] }} applications, {{ $item['share'] }} percent" @mouseenter="hovered = {{ $item['index'] }}" @mouseleave="hovered = null" @focus="hovered = {{ $item['index'] }}" @blur="hovered = null">
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
                        <strong x-text="(hovered ?? selected) === null ? '{{ $applicationsCount }}' : items[hovered ?? selected].share + '%'">{{ $applicationsCount }}</strong><span x-text="(hovered ?? selected) === null ? 'Applications' : items[hovered ?? selected].name">Applications</span><small x-show="(hovered ?? selected) !== null" x-cloak x-text="(hovered ?? selected) === null ? '' : items[hovered ?? selected].total + ' applications'"></small></div>
                </div>
                <div class="summary-category-list" role="group" aria-label="{{ $label }} breakdown">
                    @foreach($visibleItems as $item)
                        <button type="button" @click="selected = selected === {{ $item['index'] }} ? null : {{ $item['index'] }}" @mouseenter="hovered = {{ $item['index'] }}" @mouseleave="hovered = null" @focus="hovered = {{ $item['index'] }}" @blur="hovered = null" :aria-pressed="selected === {{ $item['index'] }}" :class="{ 'is-active': (hovered ?? selected) === {{ $item['index'] }} }">
                            <i style="background: {{ $item['color'] }}" aria-hidden="true"></i><span>{{ $item['name'] }}</span><strong>{{ $item['total'] }}</strong><small>{{ $item['share'] }}%</small>
                        </button>
                    @endforeach
                </div>
            @elseif($type === 'locations')
                <div class="summary-map-toolbar">
                    <span>{{ collect($map['mapped'])->sum('total') }} applications · {{ count($map['mapped']) }} mapped locations</span>
                    <div role="group" aria-label="Map zoom">
                        @if($mapDefaultIndex !== null)<button type="button" @click="selectCity({{ $mapDefaultIndex }})" :aria-pressed="mapView === defaultMapView">Top location</button>@endif
                        <button type="button" @click="mapSelected = null; mapFocus = null; mapView = '0 0 960 400'" :aria-pressed="mapView === '0 0 960 400'">Indonesia</button>
                        <button type="button" @click="mapSelected = null; mapFocus = null; mapView = '235 235 245 105'" :aria-pressed="mapView === '235 235 245 105'">Java &amp; Bali</button>
                    </div>
                </div>
                <div class="summary-indonesia-map summary-region-atlas">
                    <svg viewBox="{{ $mapDefaultView }}" x-bind:view-box.camel="mapView" role="group" aria-label="Application volume by city boundaries in Indonesia">
                        @include('summary.partials.indonesia-map-path')
                        <g aria-hidden="true">@foreach($map['districts'] as $district)<path class="summary-map-district" fill-rule="evenodd" d="{{ $district['path'] }}" />@endforeach</g>
                        @foreach($map['mapped'] as $index => $place)
                            <g class="summary-map-region" tabindex="0" role="button" aria-label="{{ $place['name'] }}: {{ $place['total'] }} applications, {{ $place['share'] }} percent" @mouseenter="mapFocus = {{ $index }}" @mouseleave="mapFocus = mapSelected" @focus="mapFocus = {{ $index }}" @blur="mapFocus = mapSelected" @click="selectCity({{ $index }})" @keydown.enter.prevent="selectCity({{ $index }})" @keydown.space.prevent="selectCity({{ $index }})" :aria-pressed="mapSelected === {{ $index }}" :class="{ 'is-selected': mapSelected === {{ $index }}, 'is-hovered': mapFocus === {{ $index }} }">
                                <path class="summary-map-city" fill="{{ $place['color'] }}" fill-rule="evenodd" d="{{ $place['path'] }}"><title>{{ implode(' / ', $place['labels']) }} · {{ $place['total'] }} applications · {{ $place['share'] }}%</title></path>
                                <text class="summary-map-region-label" x="{{ $place['labelX'] }}" y="{{ $place['labelY'] }}" text-anchor="middle" dominant-baseline="middle" :font-size="Number(mapView.split(' ')[2]) * mapLabelScale" :stroke-width="Number(mapView.split(' ')[2]) * .003" x-cloak x-show="mapSelected === {{ $index }}">{{ $place['name'] }}</text>
                            </g>
                        @endforeach
                    </svg>
                    <div class="summary-map-inset" aria-hidden="true"><span>INDONESIA</span><svg viewBox="0 0 960 400">@include('summary.partials.indonesia-map-path')<rect :x="Number(mapView.split(' ')[0])" :y="Number(mapView.split(' ')[1])" :width="Number(mapView.split(' ')[2])" :height="Number(mapView.split(' ')[3])" /></svg></div>
                    <div class="summary-atlas-caption"><span>REGIONAL VIEW</span><span>City &amp; district boundaries</span></div>
                </div>
                <div class="summary-atlas-selection" aria-live="polite">
                    <div><span>Selected location</span><strong x-text="mapFocus === null ? '{{ count($map['mapped']) ? 'Indonesia overview' : 'No geographic areas available' }}' : places[mapFocus].labels.join(' / ')">{{ $mapDefaultIndex !== null ? implode(' / ', $map['mapped'][$mapDefaultIndex]['labels']) : 'No geographic areas available' }}</strong></div>
                    <p x-text="mapFocus === null ? 'Choose a location below to inspect its area.' : places[mapFocus].total + ' applications · ' + places[mapFocus].share + '% of total · ' + places[mapFocus].accepted + ' accepted'"></p>
                </div>
                <div class="summary-map-scale"><span>Application volume</span><i aria-hidden="true"></i><span>Low → {{ $map['maximum'] }} highest</span><button type="button" @click="mapSelected = null; mapFocus = null; mapView = '0 0 960 400'">Show all Indonesia</button></div>
                <div class="summary-map-places summary-region-list">
                    @foreach($map['mapped'] as $index => $place)
                        <button type="button" @click="selectCity({{ $index }})" :aria-pressed="mapSelected === {{ $index }}"><span>{{ implode(' / ', $place['labels']) }}</span><strong>{{ $place['total'] }}</strong><small>{{ $place['share'] }}%</small><i aria-hidden="true"><span style="width: {{ $place['total'] / $map['maximum'] * 100 }}%"></span></i></button>
                    @endforeach
                </div>
                @if(count($map['unmapped']))<div class="summary-map-unmapped"><span>Outside the map</span>@foreach($map['unmapped'] as $place)<span>{{ $place['name'] }} <strong>{{ $place['total'] }}</strong></span>@endforeach<p>Remote, unrecognized locations, or locations without available city boundaries. Included in your totals.</p></div>@endif
                <p class="summary-explorer-note">Color shows application volume, not geographic area or conversion. Jakarta combines five mainland cities; Bali is represented by Denpasar. Context boundaries are simplified; highlighted city boundaries retain their source detail. <a href="https://www.naturalearthdata.com/about/terms-of-use/" target="_blank" rel="noopener">Outline: Natural Earth</a> · <a href="https://www.geoboundaries.org/api/current/gbOpen/IDN/ADM2/" target="_blank" rel="noopener">Boundaries: geoBoundaries / BPS (CC BY 3.0 IGO)</a> · <a href="https://www.geonames.org/about.html" target="_blank" rel="noopener">Cities: GeoNames</a></p>
            @else
                <div class="summary-explorer-bars">
                    @foreach($visibleItems as $item)
                        <button type="button" @mouseenter="hovered = {{ $item['index'] }}" @mouseleave="hovered = null" @focus="hovered = {{ $item['index'] }}" @blur="hovered = null" :class="{ 'is-selected': (hovered ?? selected) === {{ $item['index'] }} }" style="--explorer-color: {{ $item['color'] }}">
                            <span>{{ $item['name'] }}</span><strong>{{ $item['total'] }}</strong><small class="summary-bar-context">{{ $item['share'] }}% of applications · {{ $item['interviews'] }} interview stages · {{ $item['accepted'] }} accepted</small><i aria-hidden="true"><span style="width: {{ $item['total'] / max($maximum, $item['total']) * 100 }}%"></span></i>
                        </button>
                    @endforeach
                </div>
            @endif
            @if($type !== 'locations')
                <p class="summary-explorer-note">{{ $rows->count() > 7 ? 'Top 7 categories; remaining records grouped as Other.' : 'Select a category to explore its share and volume.' }}</p>
            @endif
        </div>
        <details class="summary-chart-data"><summary>View chart data · All {{ strtolower($label) }}</summary><div><table><caption class="sr-only">All {{ strtolower($label) }}</caption><thead><tr><th>{{ $label }}</th><th>Applications</th><th>Share</th><th>Interview stages</th><th>Accepted</th></tr></thead><tbody>@foreach($rows as $index => $row)<tr><th scope="row">{{ $row['name'] }}</th><td>{{ $row['total'] }}</td><td>{{ $items[$index]['share'] }}%</td><td>{{ $row['interviews'] }}</td><td>{{ $row['accepted'] }}</td></tr>@endforeach</tbody></table></div></details>
    </div>
@endif
