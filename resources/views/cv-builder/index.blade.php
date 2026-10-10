<x-app-layout>
    <div class="workspace-page cv-workspace">
        <div class="workspace-container">
            <header class="workspace-page-header">
                <div><p class="workspace-eyebrow">Your career, on paper</p><h1>CV Builder</h1><p>Build your story. Make every detail count.</p></div>
                <a href="{{ route('cv.generator') }}" class="cv-button cv-button-primary" wire:navigate>Choose a template <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
            </header>
            <ol class="cv-journey" aria-label="CV workflow"><li class="is-current"><span>1</span><div><strong>Build your content</strong><p>Your details, experience, and skills</p></div></li><li><span>2</span><div><strong>Choose a template</strong><p>Find a layout that fits your story</p></div></li><li><span>3</span><div><strong>Review & download</strong><p>Check your draft before exporting</p></div></li></ol>
            <livewire:cv-builder.main-builder />
        </div>
    </div>
    <livewire:cv-builder.portfolio-publish-modal />
</x-app-layout>
