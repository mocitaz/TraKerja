<x-app-layout>
    <div class="payment-workspace workspace-page-frame">
        <header class="pay-heading">
            <div>
                <a class="pay-back" href="{{ route('membership.index') }}" wire:navigate>{{ __('← Membership & credits') }}</a>
                <h1>{{ __('Payments') }}</h1>
                <p>{{ __('Your membership and purchases, in one place.') }}</p>
            </div>
            <a class="pay-link" href="{{ route('payment.history') }}" wire:navigate>{{ __('Payment history ↗') }}</a>
        </header>
        <section class="pay-availability pay-panel">
            <div class="pay-availability-copy">
                <span class="pay-eyebrow">{{ __('COMING SOON') }}</span>
                <h2>{{ __('A new way to move forward.') }}</h2>
                <p>{{ __('New purchases aren’t available here yet. Check your current plan or keep going with your career tools.') }}</p>
                <div class="pay-actions">
                    <a class="pay-button pay-button--primary" href="{{ route('membership.index') }}" wire:navigate>{{ __('View my plan') }} <span aria-hidden="true">↗</span></a>
                    <a class="pay-button" href="{{ route('tracker') }}" wire:navigate>{{ __('Back to applications') }}</a>
                </div>
            </div>
            <div class="pay-availability-note">
                <i class="ph ph-credit-card" aria-hidden="true"></i>
                <strong>{{ __('Already have an order?') }}</strong>
                <p>{{ __('Your existing orders are still in your payment history.') }}</p>
                <a class="pay-link" href="{{ route('payment.history') }}" wire:navigate>{{ __('View payment history') }} <span aria-hidden="true">↗</span></a>
            </div>
        </section>
        <footer class="pay-history-footer">
            <p>{{ __('Questions about a payment? Have your order number ready.') }}</p>
            <a class="pay-link" href="{{ route('support.index') }}" wire:navigate>{{ __('Contact support ↗') }}</a>
        </footer>
    </div>
</x-app-layout>
