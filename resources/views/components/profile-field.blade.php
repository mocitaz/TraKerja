@props(['name', 'label', 'value' => '', 'type' => 'text', 'multiline' => false, 'autocomplete' => null])
<div class="profile-field">
    <label for="profile-{{ $name }}">{{ __($label) }}</label>
    @if($multiline)
        <textarea id="profile-{{ $name }}" name="{{ $name }}" rows="4" {{ $attributes }} @error($name) aria-invalid="true" aria-describedby="profile-{{ $name }}-error" @enderror>{{ old($name, $value) }}</textarea>
    @else
        <input id="profile-{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif {{ $attributes }} @error($name) aria-invalid="true" aria-describedby="profile-{{ $name }}-error" @enderror>
    @endif
    @error($name)<p class="profile-field-error" id="profile-{{ $name }}-error">{{ $message }}</p>@enderror
</div>
