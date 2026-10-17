@props(['name' => '', 'showName' => true])
@php
    $logo = \App\Support\PlatformCatalog::logoPath($name);
    $fallback = \App\Support\PlatformCatalog::fallbackIcon($name);
@endphp
<span {{ $attributes->class(['platform-brand']) }}>
    <span class="platform-brand__mark" aria-hidden="true">
        @if($logo)<img src="{{ asset($logo) }}" @class(['platform-brand__logo--jobstreet' => $logo === 'images/platforms/jobstreet.png']) alt="" width="28" height="28" loading="lazy" decoding="async">@elseif($fallback)<i class="ph {{ $fallback }}"></i>@else<span>{{ mb_strtoupper(mb_substr($name, 0, 2)) }}</span>@endif
    </span>
    @if($showName)<span class="platform-brand__name">{{ $name ?: __('Not recorded') }}</span>@endif
</span>
