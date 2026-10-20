@php
    $locationMap = \App\Support\SummaryLocationMap::fromRows($summary['locations']);
    $mapPlaces = $locationMap['mapped'];
    $mappedTotal = collect($mapPlaces)->sum('total');
    $outsideTotal = collect($locationMap['unmapped'])->sum('total');
@endphp
<section id="summary-panel-locations" class="insights-panel insights-geography" aria-labelledby="insights-geography-title" x-data="{ view: '0 0 960 400', selected: null, hovered: null, mapTip: false, tipX: 100, tipY: 100, mapWidth: 960, places: @js($mapPlaces), init() { this.$nextTick(() => { this.mapWidth = this.$refs.atlasSvg.clientWidth || 960; }); }, selectPlace(index) { this.selected = index; this.hovered = null; this.mapTip = false; this.view = this.places[index].view; }, resetMap(view = '0 0 960 400') { this.view = view; this.selected = null; this.hovered = null; this.mapTip = false; } }" @resize.window="mapWidth = $refs.atlasSvg.clientWidth || 960; mapTip = false" @keydown.escape="resetMap()">
    <header class="insights-panel-heading"><div><h2 id="insights-geography-title">{{ __('Where is your search taking you?') }}</h2><p>{{ __('Explore the locations in your application records.') }}</p></div></header>
    <div class="insights-geography-totals"><div><i class="ph ph-map-pin-area" aria-hidden="true"></i><strong>{{ count($mapPlaces) }}</strong><span>{{ __('Mapped locations') }}</span></div><div><i class="ph ph-stack" aria-hidden="true"></i><strong>{{ $mappedTotal }}</strong><span>{{ __('Mapped applications') }}</span></div><div><i class="ph ph-globe-hemisphere-east" aria-hidden="true"></i><strong>{{ $outsideTotal }}</strong><span>{{ __('Outside the map') }}</span></div></div>
    <div class="insights-geography-layout">
        <div class="insights-atlas">
            <div class="insights-map-controls" role="group" aria-label="{{ __('Map zoom') }}"><button type="button" @click="resetMap()" :aria-pressed="view === '0 0 960 400'"><i class="ph ph-map-trifold" aria-hidden="true"></i>{{ __('Indonesia') }}</button><button type="button" @click="resetMap('235 235 245 105')" :aria-pressed="view === '235 235 245 105'"><i class="ph ph-magnifying-glass-plus" aria-hidden="true"></i>{{ __('Java & Bali') }}</button></div>
            <div class="insights-map summary-region-atlas" @mouseleave="hovered = null; mapTip = false"><svg x-ref="atlasSvg" viewBox="0 0 960 400" x-bind:view-box.camel="view" role="group" aria-label="{{ __('Application volume by city boundaries in Indonesia') }}" @pointermove="const r = $el.getBoundingClientRect(); tipX = Math.max(90, Math.min(r.width - 90, $event.clientX - r.left)); tipY = Math.max(80, $event.clientY - r.top)">
                @include('summary.partials.indonesia-map-path')
                <g aria-hidden="true">@foreach($locationMap['districts'] as $district)<path class="insights-map-district" fill-rule="evenodd" d="{{ $district['path'] }}"/>@endforeach</g>
                @foreach($mapPlaces as $index => $place)
                    @php($intensity = $place['total'] / $locationMap['maximum'])
                    <g class="insights-map-area" tabindex="0" role="button" @mouseenter="hovered = {{ $index }}; mapTip = true" @mouseleave="hovered = null; mapTip = false" @focus="hovered = {{ $index }}; mapTip = false" @blur="hovered = null" @click="selectPlace({{ $index }})" @keydown.enter.prevent="selectPlace({{ $index }})" @keydown.space.prevent="selectPlace({{ $index }})" :aria-pressed="selected === {{ $index }}" :class="{ 'is-selected': (hovered ?? selected) === {{ $index }} }" aria-label="{{ __(':value1: :value2 applications, :value3 percent', ['value1' => $place['name'], 'value2' => $place['total'], 'value3' => $place['share']]) }}">
                        <path class="summary-map-city" fill="{{ sprintf('#%02x%02x%02x', round(188 - 78 * $intensity), round(174 - 91 * $intensity), round(216 - 63 * $intensity)) }}" fill-rule="evenodd" d="{{ $place['path'] }}"><title>{{ implode(' / ', $place['labels']) }} · {{ $place['total'] }} {{ __('applications') }}</title></path>
                        <g :transform="'translate({{ $place['x'] }} {{ $place['y'] }}) scale(' + .9 / Math.max(.01, Math.min(mapWidth / Number(view.split(' ')[2]), $refs.atlasSvg.clientHeight / Number(view.split(' ')[3]))) + ')'">
                            <path class="insights-map-hit" d="M0 -18 18 0 0 18 -18 0Z"/>
                            <path class="insights-map-halo" d="M0 -13 12 0 0 13 -12 0Z"/>
                            <path class="insights-map-pin" d="M0 -8 7 0 0 8 -7 0Z"/>
                        </g>
                    </g>
                @endforeach
            </svg><div class="insights-map-tooltip" x-cloak x-show="mapTip && hovered !== null" :style="{ left: tipX + 'px', top: tipY + 'px' }" aria-hidden="true"><strong x-text="places[hovered ?? selected]?.name"></strong><span><b x-text="places[hovered ?? selected]?.total"></b> {{ __('applications') }} · <b x-text="(places[hovered ?? selected]?.share ?? 0) + '%'"></b></span></div></div>
            <div class="insights-map-caption" aria-live="polite"><span><i class="ph ph-map-pin" aria-hidden="true"></i><strong x-text="(hovered ?? selected) === null ? (view === '235 235 245 105' ? @js(__('Java & Bali')) : @js(__('Indonesia overview'))) : places[hovered ?? selected].name"></strong></span><p x-text="(hovered ?? selected) === null ? @js(__('Choose a location to inspect its area.')) : places[hovered ?? selected].total + @js(__(' applications · ')) + places[hovered ?? selected].share + '%'">{{ __('Choose a location to inspect its area.') }}</p></div>
            <div class="insights-map-scale"><span>{{ __('Fewer applications') }}</span><i aria-hidden="true"></i><span>{{ __('More applications') }}</span></div>
        </div>
        <div class="insights-location-ranking"><h3>{{ __('Locations at a glance') }}</h3>
            @forelse($mapPlaces as $index => $place)
                <button type="button" @click="selectPlace({{ $index }})" @mouseenter="hovered = {{ $index }}; mapTip = false" @mouseleave="hovered = null" @focus="hovered = {{ $index }}; mapTip = false" @blur="hovered = null" :aria-pressed="selected === {{ $index }}" :class="{ 'is-selected': (hovered ?? selected) === {{ $index }} }" @pointermove="if ($event.pointerType === 'mouse') { const r = $el.getBoundingClientRect(); $el.style.setProperty('--spot-x', ($event.clientX - r.left) + 'px'); $el.style.setProperty('--spot-y', ($event.clientY - r.top) + 'px') }" title="{{ implode(' / ', $place['labels']) }}"><span><i class="insights-location-index" aria-hidden="true">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</i>{{ $place['name'] }}</span><strong>{{ $place['total'] }}<small>{{ $place['share'] }}%</small></strong><i class="insights-ranking-track" aria-hidden="true"><span style="width: {{ $place['total'] / $locationMap['maximum'] * 100 }}%"></span></i></button>
            @empty
                <p class="insights-note">{{ __('No geographic areas available') }}</p>
            @endforelse
            @if($outsideTotal)<details class="insights-unmapped"><summary>{{ __('Outside the map') }} <strong>{{ $outsideTotal }}</strong></summary><ul>@foreach($locationMap['unmapped'] as $place)<li><span>{{ $place['name'] }}</span><strong>{{ $place['total'] }}</strong></li>@endforeach</ul><p>{{ __('Remote, unrecognized locations, or locations without available city boundaries. Included in your totals.') }}</p></details>@endif
        </div>
    </div>
    <details class="insights-data"><summary>{{ __('All locations') }}</summary>@include('summary.report.records', ['rows' => $summary['locations'], 'label' => 'Locations'])
    <p class="insights-note">{{ __('Color shows application volume, not geographic area or conversion. Jakarta combines five mainland cities; Bali is represented by Denpasar. Context boundaries are simplified; highlighted city boundaries retain their source detail.') }} <a href="https://www.naturalearthdata.com/about/terms-of-use/" target="_blank" rel="noopener noreferrer">Natural Earth</a> · <a href="https://www.geoboundaries.org/api/current/gbOpen/IDN/ADM2/" target="_blank" rel="noopener noreferrer">geoBoundaries / BPS</a> · <a href="https://www.geonames.org/about.html" target="_blank" rel="noopener noreferrer">GeoNames</a></p>
    </details>
</section>
