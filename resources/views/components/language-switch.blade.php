<form method="POST" action="{{ route('locale.update') }}" class="language-switch {{ ($location ?? '') !== 'public' ? 'language-switch--labelled' : '' }}" data-language-form>
    @csrf
    <input type="hidden" name="return_to" value="{{ request()->getRequestUri() }}">
    @if(($location ?? '') === 'public')
        @php($nextLocale = app()->getLocale() === 'en' ? 'id' : 'en')
        <input type="hidden" name="locale" value="{{ $nextLocale }}">
        <button type="submit" class="language-switch__choice" title="{{ \App\Support\AppLocale::OPTIONS[$nextLocale] }}" aria-label="{{ __('Language') }}: {{ \App\Support\AppLocale::OPTIONS[$nextLocale] }}">
            <span class="language-switch__flag" aria-hidden="true"><x-language-flag :code="app()->getLocale()" /></span>
        </button>
    @else
    <span class="language-switch__label">{{ __('Language') }}</span>
    <fieldset class="language-switch__options">
        <legend class="sr-only">{{ __('Language') }}</legend>
        @foreach(\App\Support\AppLocale::OPTIONS as $code => $label)
            <label class="language-switch__choice" title="{{ $label }}">
                <input type="radio" name="locale" value="{{ $code }}" @checked(app()->getLocale() === $code)>
                <span class="language-switch__flag" aria-hidden="true">
                    <x-language-flag :code="$code" />
                </span>
                <span class="sr-only">{{ $label }}</span>
            </label>
        @endforeach
    </fieldset>
    <noscript><button type="submit">{{ __('Apply') }}</button></noscript>
    @endif
    <p role="alert" data-language-error hidden></p>
</form>
