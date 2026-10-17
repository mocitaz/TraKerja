@php
    use App\Support\CvDocument;
    $nameParts = preg_split('/\s+/u', trim($user->name), -1, PREG_SPLIT_NO_EMPTY);
    $monogram = mb_strtoupper(mb_substr($nameParts[0] ?? '', 0, 1).(count($nameParts) > 1 ? mb_substr(end($nameParts), 0, 1) : ''));
@endphp
<header class="identity creative-identity">
    <table class="creative-masthead"><colgroup><col style="width:74%"><col style="width:26%"></colgroup><tr>
        <td class="creative-intro">
            <p class="creative-document-label">{{ __('Creative resume') }}</p>
            <h1 class="name {{ mb_strlen($user->name) > 38 ? 'creative-long-name' : '' }}">{!! CvDocument::text($user->name) !!}</h1>
            @if($experiences->first()?->position)<p class="headline">{!! CvDocument::text($experiences->first()->position) !!}</p>@endif
        </td>
        <td class="creative-portrait-cell">
            @if($photo)
                <div class="creative-photo-frame"><img class="creative-portrait" src="{{ $photo }}" alt="{{ __('Profile photo') }}"></div>
            @else
                <div class="creative-monogram">{{ $monogram }}</div>
            @endif
        </td>
    </tr></table>
    <div class="creative-contact-block">
        <div class="contacts">@foreach($contacts as $contact)<span>{!! CvDocument::text($contact) !!}</span>@if(!$loop->last)<span class="separator">/</span>@endif @endforeach</div>
        @if($links)<div class="profile-links">@foreach($links as $label => $url)<a href="{{ $url }}">{{ __($label) }}: {!! CvDocument::text(preg_replace('#^https?://#i', '', $url)) !!}</a>@if(!$loop->last)<span class="separator">/</span>@endif @endforeach</div>@endif
    </div>
</header>
