<x-app-layout>
    <div class="workspace-page cv-workspace">
        <div class="workspace-container">
            <header class="workspace-page-header">
                <div><h1>{{ __('CV Builder') }}</h1><p>{{ __('Build your story. Make every detail count.') }}</p></div>
                <a href="{{ route('cv.generator') }}" class="cv-button cv-button-primary" wire:navigate>{{ __('Choose a template') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            </header>
            <ol class="cv-journey" aria-label="{{ __('CV workflow') }}"><li class="is-current"><span>1</span><div><strong>{{ __('Build your content') }}</strong><p>{{ __('Your details, experience, and skills') }}</p></div></li><li><span>2</span><div><strong>{{ __('Choose a template') }}</strong><p>{{ __('Find a layout that fits your story') }}</p></div></li><li><span>3</span><div><strong>{{ __('Review & download') }}</strong><p>{{ __('Check your draft before exporting') }}</p></div></li></ol>
            <livewire:cv-builder.main-builder />
        </div>
    </div>
    <livewire:cv-builder.portfolio-publish-modal />
</x-app-layout>
