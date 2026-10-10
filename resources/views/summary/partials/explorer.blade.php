@php
    $palette = ['#a95b91', '#648eaf', '#9474b2', '#c78268', '#6f968d', '#aa9662', '#7b829f', '#b6b4bf'];
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
    $visualTitle = match($type) { 'sources' => 'Where you apply', 'roles' => 'Roles you pursue', 'locations' => 'Your location mix', 'companies' => 'Companies on your radar', default => 'How you want to work' };
@endphp
@if($rows->isEmpty())
    <div class="summary-analysis-empty"><h3>No records in this period.</h3><p>Your saved applications will populate this analysis.</p></div>
@else
    <div class="summary-explorer" x-data="{ hovered: null, items: @js($items), mapView: '0 0 960 400', mapFocus: null, mapSelected: null, selectCity(i) { this.mapFocus = i; this.mapSelected = i; this.mapView = this.places[i].view; }, places: @js($map['mapped'] ?? []) }">
        <div class="summary-explorer-visual">
            <header><h3>{{ $visualTitle }}</h3><span>{{ $rows->count() }} {{ strtolower($label) }}</span></header>
            @if(in_array($type, ['sources', 'employment']))
                <div class="summary-explorer-donut">
                    <svg viewBox="0 0 200 200" role="group" aria-label="{{ $label }} distribution">
                        <circle cx="100" cy="100" r="76" fill="none" stroke="#e7e3ea" stroke-width="20" />
                        @php($offset = 0)
                        @foreach($visibleItems as $item)
                            @php($segment = $item['total'] / max(1, $applicationsCount) * 100)
                            <g tabindex="0" role="img" aria-label="{{ $item['name'] }}: {{ $item['total'] }} applications, {{ $item['share'] }} percent" @mouseenter="hovered = {{ $item['index'] }}" @mouseleave="hovered = null" @focus="hovered = {{ $item['index'] }}" @blur="hovered = null">
                                <circle cx="100" cy="100" r="76" pathLength="100" fill="none" stroke="{{ $item['color'] }}" stroke-width="20" stroke-dasharray="{{ $segment }} {{ 100 - $segment }}" stroke-dashoffset="{{ -$offset }}" transform="rotate(-90 100 100)" class="explorer-ring" :class="{ 'is-selected': hovered === {{ $item['index'] }}, 'is-muted': hovered !== null && hovered !== {{ $item['index'] }}" />
                            </g>
                            @php($offset += $segment)
                        @endforeach
                    </svg>
                    <div aria-live="polite">
                        @if($type === 'sources')
                            @foreach($visibleItems as $item)
                                <span class="summary-donut-platform" x-show="hovered === {{ $item['index'] }}" x-cloak><x-platform-brand :name="$item['name']" :show-name="false" /></span>
                            @endforeach
                        @endif
                        <strong x-text="hovered === null ? '{{ $applicationsCount }}' : items[hovered].share + '%'">{{ $applicationsCount }}</strong><span x-text="hovered === null ? 'Applications' : items[hovered].name">Applications</span><small x-show="hovered !== null" x-cloak x-text="hovered === null ? '' : items[hovered].total + ' applications'"></small></div>
                </div>
            @elseif($type === 'locations')
                <div class="summary-map-toolbar"><span>{{ collect($map['mapped'])->sum('total') }} applications mapped</span><div role="group" aria-label="Map zoom"><button type="button" @click="mapSelected = null; mapView = '0 0 960 400'" :aria-pressed="mapView === '0 0 960 400'">Indonesia</button><button type="button" @click="mapSelected = null; mapView = '235 235 245 105'" :aria-pressed="mapView === '235 235 245 105'">Java & Bali</button><button type="button" @click="mapSelected = null; mapView = '248 258 20 22'" :aria-pressed="mapView === '248 258 20 22'">Jakarta area</button></div></div>
                <div class="summary-indonesia-map">
                    <svg viewBox="0 0 960 400" x-bind:view-box.camel="mapView" role="group" aria-label="Applications across Indonesia">
                        @include('summary.partials.indonesia-map-path')
                        @foreach($map['mapped'] as $index => $place)
                            <g class="summary-map-marker" tabindex="0" role="button" aria-label="{{ $place['name'] }}: {{ $place['total'] }} applications, {{ $place['share'] }} percent" @mouseenter="mapFocus = {{ $index }}" @focus="mapFocus = {{ $index }}" @click="selectCity({{ $index }})" @keydown.enter.prevent="selectCity({{ $index }})" @keydown.space.prevent="selectCity({{ $index }})" :aria-pressed="mapSelected === {{ $index }}" :class="{ 'is-selected': mapSelected === {{ $index }}, 'is-hovered': mapFocus === {{ $index }} }">
                                @if($place['path'])<path class="summary-map-city" fill="{{ $place['color'] }}" fill-rule="evenodd" d="{{ $place['path'] }}"><title>{{ implode(' / ', $place['labels']) }} · {{ $place['total'] }} applications · {{ $place['share'] }}%</title></path>@endif
                                <circle class="summary-map-halo" :r="Number(mapView.split(' ')[2]) / 960 * 10" cx="{{ $place['x'] }}" cy="{{ $place['y'] }}" r="10" />
                                <circle class="summary-map-point" :r="Number(mapView.split(' ')[2]) / 960 * 2.5" cx="{{ $place['x'] }}" cy="{{ $place['y'] }}" r="2.5" />
                            </g>
                        @endforeach
                    </svg>
                    <div class="summary-map-readout" aria-live="polite"><strong x-text="mapFocus === null ? 'Explore Indonesia' : places[mapFocus].labels.join(' / ')">Explore Indonesia</strong><span x-text="mapFocus === null ? 'Hover a point or choose a location below' : places[mapFocus].total + ' applications · ' + places[mapFocus].share + '% of total · ' + places[mapFocus].accepted + ' accepted'">Hover a point or choose a location below</span></div>
                </div>
                <div class="summary-map-scale"><span>Application volume</span><i aria-hidden="true"></i><span>Low → {{ $map['maximum'] }} highest</span><button type="button" @click="mapSelected = null; mapFocus = null; mapView = '0 0 960 400'">Reset selection</button></div>
                <div class="summary-map-places">@foreach($map['mapped'] as $index => $place)<button type="button" @click="selectCity({{ $index }})" :aria-pressed="mapSelected === {{ $index }}">{{ implode(' / ', $place['labels']) }} <strong>{{ $place['total'] }}</strong></button>@endforeach</div>
                @if(count($map['unmapped']))<div class="summary-map-unmapped"><span>Not plotted</span>@foreach($map['unmapped'] as $place)<span>{{ $place['name'] }} <strong>{{ $place['total'] }}</strong></span>@endforeach</div>@endif
                <p class="summary-explorer-note">City boundaries; Jakarta combines its five mainland cities. Bali uses Denpasar as a representative city. Remote and unmatched locations are not plotted. <a href="https://www.naturalearthdata.com/about/terms-of-use/" target="_blank" rel="noopener">Outline: Natural Earth</a> · <a href="https://www.geoboundaries.org/api/current/gbOpen/IDN/ADM2/" target="_blank" rel="noopener">City boundaries: geoBoundaries / BPS (CC BY 3.0 IGO)</a> · <a href="https://www.geonames.org/about.html" target="_blank" rel="noopener">Cities: GeoNames</a></p>
            @else
                <div class="summary-explorer-bars">
                    @foreach($visibleItems as $item)
                        <button type="button" @mouseenter="hovered = {{ $item['index'] }}" @mouseleave="hovered = null" @focus="hovered = {{ $item['index'] }}" @blur="hovered = null" :class="{ 'is-selected': hovered === {{ $item['index'] }} }" style="--explorer-color: {{ $item['color'] }}">
                            <span>{{ $item['name'] }}</span><strong>{{ $item['total'] }}</strong><span class="summary-bar-tooltip" x-show="hovered === {{ $item['index'] }}" x-cloak>{{ $item['share'] }}% · {{ $item['interviews'] }} interview stages · {{ $item['accepted'] }} accepted</span><i aria-hidden="true"><span style="width: {{ $item['total'] / max($maximum, $item['total']) * 100 }}%"></span></i>
                        </button>
                    @endforeach
                </div>
            @endif
            @if($type !== 'locations')
                <p class="summary-explorer-note">{{ $rows->count() > 7 ? 'Top 7 categories; remaining records grouped as Other.' : 'Hover or focus the chart to explore.' }}</p>
            @endif
        </div>
        <details class="summary-chart-data"><summary>View chart data · All {{ strtolower($label) }}</summary><div><table><caption class="sr-only">All {{ strtolower($label) }}</caption><thead><tr><th>{{ $label }}</th><th>Applications</th><th>Share</th><th>Interview stages</th><th>Accepted</th></tr></thead><tbody>@foreach($rows as $index => $row)<tr><th scope="row">{{ $row['name'] }}</th><td>{{ $row['total'] }}</td><td>{{ $items[$index]['share'] }}%</td><td>{{ $row['interviews'] }}</td><td>{{ $row['accepted'] }}</td></tr>@endforeach</tbody></table></div></details>
    </div>
@endif
