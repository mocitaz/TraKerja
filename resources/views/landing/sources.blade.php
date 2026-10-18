@php
    $catalogSources = array_map(fn ($source) => $source === 'JobsDB' ? 'JobStreet' : $source, \App\Support\PlatformCatalog::OPTIONS);
    $sourceOptions = array_values(array_unique(array_merge(['JobStreet', 'LinkedIn', 'Glints', 'Indeed', 'Kalibrr', 'Dealls', 'Glassdoor', 'KitaLulus'], $catalogSources)));
    $sourceRows = [$sourceOptions];
@endphp
<div class="lp-sources" data-source-marquee data-reveal>
    <div class="lp-sources-heading"><p>{{ __('landing.sources.title') }}</p><button type="button" class="lp-sources-toggle" data-source-toggle aria-pressed="false" data-pause="{{ __('landing.sources.pause') }}" data-resume="{{ __('landing.sources.resume') }}">@include('landing.icon', ['name' => 'pause'])<span>{{ __('landing.sources.pause') }}</span></button></div>
    <div class="lp-sources-rows" aria-label="{{ __('landing.sources.label') }}">
        @foreach($sourceRows as $sourceRow)
            <div class="lp-sources-row"><div class="lp-sources-track">
                @for($copy = 0; $copy < 2; $copy++)
                    <ul class="lp-sources-group" @if($copy) aria-hidden="true" @endif>
                        @foreach($sourceRow as $source)
                            @php($logo = \App\Support\PlatformCatalog::logoPath($source))
                            <li @if(!$copy) data-application-source="{{ $source }}" @endif>
                                @if($logo)<img draggable="false" src="{{ asset($logo) }}" @class(['lp-source-jobstreet' => $source === 'JobStreet']) width="27" height="27" alt="" decoding="async">@endif
                                <span>{{ in_array($source, ['Email', 'Website Company', 'Other']) ? __($source) : $source }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endfor
            </div></div>
        @endforeach
    </div>
</div>
