<x-app-layout>
    <div class="community-page workspace-page-frame">
        @include('community.welcome', ['isModal' => false])
        <a class="community-page-back" href="{{ route('dashboard') }}" wire:navigate>← Back to your workspace</a>
    </div>
</x-app-layout>
