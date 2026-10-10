<x-app-layout>
    @include('payment.partials.checkout', ['isAddon' => true, 'price' => $addonPrice, 'days' => null])
</x-app-layout>
