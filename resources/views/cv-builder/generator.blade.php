<x-app-layout>
    @php
        $monetization = \App\Models\Setting::isMonetizationEnabled();
        $premium = $user->isPremium();
        $hasContent = $user->experiences_count + $user->educations_count + $user->skills_count + $user->projects_count + $user->organizations_count + $user->achievements_count > 0 || filled($user->profile?->bio);
        $templates = [
            'minimal' => ['name' => 'Minimal', 'description' => 'A classic black-and-white resume with centered contact details, compact entries, and standard headings.', 'color' => 'blue', 'layout' => 'Centered header · Single column'],
            'professional' => ['name' => 'Professional', 'description' => 'A formal corporate resume with clean sans-serif type, employer-first entries, and right-aligned dates.', 'color' => 'lavender', 'layout' => 'Corporate · Black & white'],
            'creative' => ['name' => 'Creative', 'description' => 'A studio-style resume with a profile portrait, terracotta accents, and projects in focus.', 'color' => 'peach', 'layout' => 'Studio editorial · Portrait'],
            'elegant' => ['name' => 'Elegant', 'description' => 'A monochrome editorial resume with a left-aligned serif signature and understated typography.', 'color' => 'pink', 'layout' => 'Editorial · Monochrome'],
        ];
    @endphp
    <div class="workspace-page cv-workspace" x-data="{ template: 'minimal', loading: true }">
        <div class="workspace-container">
            <header class="workspace-page-header"><div><p class="workspace-eyebrow">Make your story presentable</p><h1>Choose your CV template</h1><p>Same content. A layout that feels like you.</p></div><a href="{{ route('cv.builder') }}" class="cv-button cv-button-secondary" wire:navigate><i class="ph ph-arrow-left" aria-hidden="true"></i>Back to builder</a></header>
            <ol class="cv-journey" aria-label="CV workflow"><li><span>1</span><div><strong>Build your content</strong><p>Saved in your builder</p></div></li><li class="is-current"><span>2</span><div><strong>Choose a template</strong><p>Preview with your own details</p></div></li><li><span>3</span><div><strong>Review & download</strong><p>Open the export workspace</p></div></li></ol>
            @if(session('error'))<p class="cv-page-error" role="alert">{{ session('error') }}</p>@endif
            @if(!$hasContent)<div class="cv-empty-draft"><div><strong>Your story starts here.</strong><p>This is a draft with your account details. Add education, skills, projects, or experience to give it substance.</p></div><a href="{{ route('cv.builder') }}" class="cv-button cv-button-secondary" wire:navigate>Add your content</a></div>@endif
            <div class="cv-generator-layout">
                <aside class="cv-template-sidebar">
                    <header class="cv-template-heading"><h2>Pick your layout</h2><p>{{ $templatesCount }} templates available for your account.</p></header>
                    <fieldset class="cv-template-options"><legend class="sr-only">CV template</legend>
                        @foreach($templates as $key => $option)
                            @php $accessible = !$monetization || $premium || !in_array($key, ['creative', 'elegant']); @endphp
                            <div class="cv-template-option cv-template-{{ $option['color'] }}" :class="{ 'is-selected': template === '{{ $key }}' }">
                                @if($accessible)
                                    <label><input type="radio" name="cv-template" value="{{ $key }}" x-model="template" @change="loading = true" @checked($key === 'minimal')><span class="cv-template-name">{{ $option['name'] }}</span><span class="cv-template-check" aria-hidden="true"><i class="ph ph-check"></i></span></label>
                                @else
                                    <div class="cv-template-locked"><strong>{{ $option['name'] }}</strong><span>Premium</span></div>
                                @endif
                                <p>{{ $option['description'] }}</p><span class="cv-template-layout">{{ $option['layout'] }}</span>
                                @if($key === 'creative' && $accessible)<p class="cv-template-photo-note">{{ $user->logo ? 'Uses your uploaded profile photo.' : 'Add a profile photo for the portrait layout.' }} <a href="{{ route('profile.edit') }}" style="text-decoration:underline">Edit profile photo</a></p>@endif
                                @if(!$accessible)<a href="{{ route('membership.index') }}" class="cv-template-upgrade">View membership <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@endif
                            </div>
                        @endforeach
                    </fieldset>
                    <div class="cv-export-note"><strong>{{ $remainingExports === 'unlimited' ? 'Unlimited exports' : $remainingExports.' exports remaining' }}</strong><p>{{ $remainingExports === 'unlimited' ? 'Review your draft before downloading.' : 'Available in your current monthly allowance.' }}</p><a href="{{ route('membership.index') }}" wire:navigate>Membership & credits</a></div>
                </aside>
                <section class="cv-generator-preview">
                    <header class="cv-generator-preview-header"><div><h2><span x-text="template.charAt(0).toUpperCase() + template.slice(1)"></span> preview</h2><p>PDF preview · Saved content · No export credit used</p></div><span class="cv-draft-badge">Draft</span></header>
                    <div class="cv-generator-paper"><p x-show="loading" class="cv-preview-loading" role="status">Loading your draft...</p><iframe title="CV template preview with your saved content" :src="@js(route('cv-builder.preview.get', ['canvas' => 1])) + '&template=' + template" @load="loading = false"></iframe></div>
                    <footer class="cv-generator-preview-footer"><div><strong>Check before you export</strong><p>Minimal and Professional use a text-first, single-column layout. Parsing results vary between recruitment systems.</p></div><form method="POST" action="{{ route('cv-builder.preview') }}">@csrf<input type="hidden" name="template" :value="template"><button type="submit" class="cv-button cv-button-primary">Review & export <i class="ph ph-arrow-right" aria-hidden="true"></i></button></form></footer>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
