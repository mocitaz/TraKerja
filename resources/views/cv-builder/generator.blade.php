<x-app-layout>
    @php
        $monetization = \App\Models\Setting::isMonetizationEnabled();
        $premium = $user->isPremium();
        $hasContent = $user->experiences_count + $user->educations_count + $user->skills_count + $user->projects_count + $user->organizations_count + $user->achievements_count > 0 || filled($user->profile?->bio);
        $templates = [
            'minimal' => ['name' => 'Minimal', 'description' => __('A classic black-and-white resume with centered contact details, compact entries, and standard headings.'), 'color' => 'blue', 'layout' => 'Centered header · Single column'],
            'professional' => ['name' => 'Professional', 'description' => __('A formal corporate resume with clean sans-serif type, employer-first entries, and right-aligned dates.'), 'color' => 'lavender', 'layout' => 'Corporate · Black & white'],
            'creative' => ['name' => 'Creative', 'description' => __('A studio-style resume with a profile portrait, terracotta accents, and projects in focus.'), 'color' => 'peach', 'layout' => 'Studio editorial · Portrait'],
            'elegant' => ['name' => 'Elegant', 'description' => __('A monochrome editorial resume with a left-aligned serif signature and understated typography.'), 'color' => 'pink', 'layout' => 'Editorial · Monochrome'],
        ];
    @endphp
    <div class="workspace-page cv-workspace" x-data="{ template: 'minimal', loading: true }">
        <div class="workspace-container">
            <header class="workspace-page-header"><div><p class="workspace-eyebrow">{{ __('Make your story presentable') }}</p><h1>{{ __('Choose your CV template') }}</h1><p>{{ __('Same content. A layout that feels like you.') }}</p></div><a href="{{ route('cv.builder') }}" class="cv-button cv-button-secondary" wire:navigate><i class="ph ph-arrow-left" aria-hidden="true"></i>{{ __('Back to builder') }}</a></header>
            <ol class="cv-journey" aria-label="{{ __('CV workflow') }}"><li><span>1</span><div><strong>{{ __('Build your content') }}</strong><p>{{ __('Saved in your builder') }}</p></div></li><li class="is-current"><span>2</span><div><strong>{{ __('Choose a template') }}</strong><p>{{ __('Preview with your own details') }}</p></div></li><li><span>3</span><div><strong>{{ __('Review & download') }}</strong><p>{{ __('Open the export workspace') }}</p></div></li></ol>
            @if(session('error'))<p class="cv-page-error" role="alert">{{ session('error') }}</p>@endif
            @if(!$hasContent)<div class="cv-empty-draft"><div><strong>{{ __('Your story starts here.') }}</strong><p>{{ __('This is a draft with your account details. Add education, skills, projects, or experience to give it substance.') }}</p></div><a href="{{ route('cv.builder') }}" class="cv-button cv-button-secondary" wire:navigate>{{ __('Add your content') }}</a></div>@endif
            <div class="cv-generator-layout">
                <aside class="cv-template-sidebar">
                    <header class="cv-template-heading"><h2>{{ __('Pick your layout') }}</h2><p>{{ __(':value1 templates available for your account.', ['value1' => ($templatesCount)]) }}</p></header>
                    <fieldset class="cv-template-options"><legend class="sr-only">{{ __('CV template') }}</legend>
                        @foreach($templates as $key => $option)
                            @php $accessible = !$monetization || $premium || !in_array($key, ['creative', 'elegant']); @endphp
                            <div class="cv-template-option cv-template-{{ $option['color'] }}" :class="{ 'is-selected': template === '{{ $key }}' }">
                                @if($accessible)
                                    <label><input type="radio" name="cv-template" value="{{ $key }}" x-model="template" @change="loading = true" @checked($key === 'minimal')><span class="cv-template-name">{{ $option['name'] }}</span><span class="cv-template-check" aria-hidden="true"><i class="ph ph-check"></i></span></label>
                                @else
                                    <div class="cv-template-locked"><strong>{{ $option['name'] }}</strong><span>{{ __('Premium') }}</span></div>
                                @endif
                                <p>{{ $option['description'] }}</p><span class="cv-template-layout">{{ $option['layout'] }}</span>
                                @if($key === 'creative' && $accessible)<p class="cv-template-photo-note">{{ $user->logo ? __('Uses your uploaded profile photo.') : __('Add a profile photo for the portrait layout.') }} <a href="{{ route('profile.edit') }}" style="text-decoration:underline">{{ __('Edit profile photo') }}</a></p>@endif
                                @if(!$accessible)<a href="{{ route('membership.index') }}" class="cv-template-upgrade">{{ __('View membership') }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@endif
                            </div>
                        @endforeach
                    </fieldset>
                    <div class="cv-export-note"><strong>{{ $remainingExports === 'unlimited' ? __('Unlimited exports') : $remainingExports.__(' exports remaining') }}</strong><p>{{ $remainingExports === 'unlimited' ? __('Review your draft before downloading.') : __('Available in your current monthly allowance.') }}</p><a href="{{ route('membership.index') }}" wire:navigate>{{ __('Membership & credits') }}</a></div>
                </aside>
                <section class="cv-generator-preview">
                    <header class="cv-generator-preview-header"><div><h2><span x-text="template.charAt(0).toUpperCase() + template.slice(1)"></span> {{ __('preview') }}</h2><p>{{ __('PDF preview · Saved content · No export credit used') }}</p></div><span class="cv-draft-badge">{{ __('Draft') }}</span></header>
                    <div class="cv-generator-paper"><p x-show="loading" class="cv-preview-loading" role="status">{{ __('Loading your draft...') }}</p><iframe title="{{ __('CV template preview with your saved content') }}" :src="@js(route('cv-builder.preview.get', ['canvas' => 1])) + '&template=' + template" @load="loading = false"></iframe></div>
                    <footer class="cv-generator-preview-footer"><div><strong>{{ __('Check before you export') }}</strong><p>{{ __('Minimal and Professional use a text-first, single-column layout. Parsing results vary between recruitment systems.') }}</p></div><form method="POST" action="{{ route('cv-builder.preview') }}">@csrf<input type="hidden" name="template" :value="template"><button type="submit" class="cv-button cv-button-primary">{{ __('Review & export') }} <i class="ph ph-arrow-right" aria-hidden="true"></i></button></form></footer>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
