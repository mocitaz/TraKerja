<div class="community-overlay" x-data="{ open: true }" x-cloak x-show="open" @click.self="open = false"
     x-transition:enter="community-fade" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="community-fade" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    @include('community.welcome', ['isModal' => true])
</div>
