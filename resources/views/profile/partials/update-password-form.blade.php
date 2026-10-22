<form method="post" action="{{ route('password.update') }}">
    @csrf @method('put')
    <input type="hidden" name="username" value="{{ $user->email }}" autocomplete="username">
    <p class="profile-password-help">{{ __('Use at least 8 characters with uppercase and lowercase letters, a number, and a symbol (@ $ ! % * # ? &).') }}</p>
    <div class="pf-password-grid">
    @foreach(['current_password' => ['Current password', 'current-password'], 'password' => ['New password', 'new-password'], 'password_confirmation' => ['Confirm new password', 'new-password']] as $name => [$label, $autocomplete])
        <div class="profile-field" x-data="{ visible: false }">
            <label for="profile-security-{{ $name }}">{{ __($label) }}</label>
            <div class="profile-password-input"><input id="profile-security-{{ $name }}" name="{{ $name }}" type="password" :type="visible ? 'text' : 'password'" autocomplete="{{ $autocomplete }}" required @error($name, 'updatePassword') aria-invalid="true" aria-describedby="profile-security-{{ $name }}-error" @enderror><button type="button" @click="visible = !visible" :aria-pressed="visible.toString()" :aria-label="visible ? @js(__('Hide :field', ['field'=>__($label)])) : @js(__('Show :field', ['field'=>__($label)]))"><i class="ph" :class="visible ? 'ph-eye-slash' : 'ph-eye'" aria-hidden="true"></i></button></div>
            @error($name, 'updatePassword')<p id="profile-security-{{ $name }}-error" class="profile-field-error">{{ $message }}</p>@enderror
        </div>
    @endforeach
    </div>
    <div class="profile-form-footer"><span>{{ __('Use a password you haven’t used here before.') }}</span><button type="submit" class="profile-primary">{{ __('Update password') }}</button></div>
</form>
