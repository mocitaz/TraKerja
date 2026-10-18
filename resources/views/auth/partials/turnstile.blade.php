@if (!app()->environment('local', 'testing') && !in_array(request()->getHost(), ['localhost', '127.0.0.1']))
    <div class="auth-turnstile"><div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key', '0x4AAAAAADVwvVUur2OE6_b9') }}" data-size="compact" data-theme="auto"></div></div>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
