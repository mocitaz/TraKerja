@component('mail::message')
@php
    $greet = $greeting ?? __('emails.common.hello');
    $color = match ($level ?? null) {
        'success' => 'success',
        'error' => 'error',
        default => 'primary',
    };
@endphp

# {{ $greet }}

@foreach ($introLines as $line)
{{ $line }}

@endforeach

@isset($actionText)
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

@foreach ($outroLines as $line)
{{ $line }}

@endforeach

@if (! empty($salutation))
{{ $salutation }}
@else
{{ __('emails.common.signoff') }}
{{ __('emails.common.team') }}
@endif

@isset($actionText)
@slot('subcopy')
{{ __('emails.common.fallback') }} [{{ $displayableActionUrl }}]({{ $actionUrl }})
@endslot
@endisset

@endcomponent


