@component('mail::message')
@php
    $greet = $greeting ?? 'Hello!';
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
Regards,
{{ config('app.name') }}
@endif

@isset($actionText)
@slot('subcopy')
@component('mail::subcopy')
Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda: [{{ $displayableActionUrl }}]({{ $actionUrl }})
@endcomponent
@endslot
@endisset

@endcomponent


