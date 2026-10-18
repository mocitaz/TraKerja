@props(['name', 'label', 'type' => 'text', 'autocomplete' => null, 'value' => '', 'hint' => null, 'placeholder' => null, 'minlength' => null])
<div class="auth-field">
    <label for="{{ $name }}">{{ $label }}</label>
    <div class="auth-input-wrap">
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" @if($type !== 'password') value="{{ $value }}" @endif @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif @if($minlength) minlength="{{ $minlength }}" @endif @if($placeholder) placeholder="{{ $placeholder }}" @endif required @if($errors->has($name)) aria-invalid="true" @endif aria-describedby="{{ $name }}-error{{ $hint ? ' '.$name.'-hint' : '' }}">
        @if($type === 'password')<button class="auth-password-toggle" type="button" data-password-toggle="{{ $name }}" aria-controls="{{ $name }}" aria-pressed="false" aria-label="{{ __('auth-ui.show_password') }}" data-show-label="{{ __('auth-ui.show_password') }}" data-hide-label="{{ __('auth-ui.hide_password') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/><path class="auth-eye-slash" d="m3 3 18 18"/></svg></button>@endif
    </div>
    @if($hint)<p class="auth-field-hint" id="{{ $name }}-hint">{{ $hint }}</p>@endif
    <p class="auth-field-error" id="{{ $name }}-error">@error($name){{ $message }}@enderror</p>
</div>
