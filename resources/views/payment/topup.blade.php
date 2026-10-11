<x-app-layout>
    @include('payment.partials.checkout', ['isAddon' => true, 'price' => $addonPrice, 'days' => null, 'packageType' => $packageType ?? 'addon_10'])
</x-app-layout>
