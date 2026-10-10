<x-app-layout>
    @include('payment.partials.checkout', ['isAddon' => false, 'price' => $premiumPrice, 'days' => $premiumDuration, 'packageType' => 'premium'])
</x-app-layout>
