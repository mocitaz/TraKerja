<div x-data="{}">
    <template x-teleport="body">
        <div class="cv-modal-overlay" role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showModal" aria-labelledby="site-settings-title" x-show="$wire.showModal" x-cloak @click.self="$wire.closeModal()">
            <div class="site-settings-dialog">
                <header class="cv-modal-header"><div><h2 id="site-settings-title">Your portfolio site</h2><p>A place for your work, experience, and story.</p></div><button type="button" class="cv-close" wire:click="closeModal" aria-label="Close portfolio settings"><i class="ph ph-x" aria-hidden="true"></i></button></header>
                <form wire:submit="publish">
                    <div class="site-settings-body">
                        <div class="site-visibility"><div><span class="site-status-dot {{ $isPublished ? 'is-live' : '' }}"></span><strong>{{ $isPublished ? 'Published' : 'Private draft' }}</strong></div>@if($liveUrl)<a href="{{ $liveUrl }}" target="_blank" rel="noopener noreferrer">Open live site <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>@endif<p>{{ $isPublished ? 'Your saved CV updates appear on your live site automatically.' : 'Your site stays private until you publish it.' }}</p></div>
                        <div class="site-address-field"><label for="portfolio-address">Your site address</label><div><span aria-hidden="true">@</span><input id="portfolio-address" type="text" wire:model.live.debounce.400ms="slug" maxlength="50" required autocomplete="off" spellcheck="false" placeholder="your-name" aria-describedby="portfolio-address-help{{ $errors->has('slug') ? ' portfolio-slug-error' : '' }}"></div><p id="portfolio-address-help">{{ $proposedUrl }}</p>@error('slug')<p id="portfolio-slug-error" class="cv-field-error" role="alert">{{ $message }}</p>@enderror</div>
                        <fieldset class="site-theme-options"><legend>Choose your look</legend><p>Preview the theme with your saved content before publishing.</p><div>
                            @foreach(['slate' => 'Cloud', 'dark' => 'Midnight', 'emerald' => 'Sage', 'violet' => 'Lilac'] as $key => $label)
                                <label @class(['site-theme-option', 'is-selected' => $theme === $key])><input type="radio" name="portfolio-theme" wire:model.live="theme" value="{{ $key }}"><span class="site-theme-swatch site-theme-{{ $key }}" aria-hidden="true"><span></span><span></span><span></span></span><strong>{{ $label }}</strong></label>
                            @endforeach
                        </div>@error('theme')<p class="cv-field-error" role="alert">{{ $message }}</p>@enderror</fieldset>
                        <a href="{{ route('portfolio.preview', ['theme' => in_array($theme, ['slate','dark','emerald','violet']) ? $theme : 'slate']) }}" target="_blank" rel="noopener noreferrer" class="site-preview-action">Preview your site <i class="ph ph-arrow-up-right" aria-hidden="true"></i><span>Private preview · Opens a new tab</span></a>
                        @if(!$hasContent)<div class="site-content-guidance"><strong>Add a little about yourself first.</strong><p>A summary or one CV section is enough to start. Empty sections stay hidden.</p><a href="{{ route('cv.builder') }}" wire:navigate>Open CV Builder</a></div>@endif
                        <div class="site-public-note"><strong>What becomes public?</strong><p>Your name, avatar, email ({{ $user->email }}), summary, links, and saved CV sections. Your phone number and tracker are not displayed.</p><p>Publishing shares the latest saved content. Changing your address makes the previous address unavailable.</p></div>
                        @if($user->portfolio_custom_domain)<p class="site-domain-note">Saved domain: {{ $user->portfolio_custom_domain }}. Domain connection is not verified in this app; use your TraKerja address.</p>@endif
                        @if(!$canPublish)<p class="cv-field-error">Publishing requires Premium. Private preview is available.</p><a href="{{ route('membership.index') }}" class="site-membership-link" wire:navigate>View membership</a>@endif
                        @error('content')<p class="cv-field-error" role="alert">{{ $message }}</p>@enderror
                        @error('access')<p class="cv-field-error" role="alert">{{ $message }}</p>@enderror
                    </div>
                    <footer class="site-settings-footer">
                        @if($isPublished)<button type="button" class="site-unpublish" wire:click="unpublish" wire:loading.attr="disabled" wire:target="publish,unpublish">Unpublish</button>@else<button type="button" class="cv-button cv-button-secondary" wire:click="closeModal">Cancel</button>@endif
                        <button type="submit" class="cv-button cv-button-primary" wire:loading.attr="disabled" wire:target="publish,unpublish" @disabled(!$hasContent || !$canPublish)><span wire:loading.remove wire:target="publish">{{ $isPublished ? 'Save site settings' : 'Publish site' }}</span><span wire:loading wire:target="publish">Saving...</span></button>
                    </footer>
                </form>
            </div>
        </div>
    </template>
</div>
