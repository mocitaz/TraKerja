<?php

namespace App\Livewire\CvBuilder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class PortfolioPublishModal extends Component
{
    public $showModal = false;
    public $slug = '';
    public $isPublished = false;
    public $portfolioUrl = '';
    public $theme = 'slate';
    public $customDomain = '';

    public $validThemes = ['slate', 'dark', 'emerald', 'violet'];

    protected $listeners = ['openPublishModal' => 'openModal'];

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->slug         = $user->portfolio_slug ?? Str::slug($user->name);
        $this->isPublished  = $user->is_portfolio_published;
        $this->theme        = $user->portfolio_theme ?? 'slate';
        $this->customDomain = $user->portfolio_custom_domain ?? '';
        $this->updateUrl();
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function updatedSlug()
    {
        $this->slug = Str::slug($this->slug);
        $this->updateUrl();
    }

    private function updateUrl()
    {
        $this->portfolioUrl = url('/@' . $this->slug);
    }

    public function publish()
    {
        $this->validate([
            'slug'         => 'required|string|alpha_dash|max:50|unique:users,portfolio_slug,' . Auth::id(),
            'theme'        => 'required|in:slate,dark,emerald,violet',
            'customDomain' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\-\.]+$/',
        ], [
            'slug.alpha_dash'     => 'Slug may only contain letters, numbers, dashes, and underscores.',
            'slug.unique'         => 'This slug is already taken. Please choose another.',
            'customDomain.regex'  => 'Custom domain format is invalid.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (\App\Models\Setting::isMonetizationEnabled() && !$user->isPremium()) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'title' => 'Premium Required',
                'message' => 'Publishing your personal portfolio site is only available for Premium users.',
            ]);
            return;
        }

        $user->update([
            'portfolio_slug'          => $this->slug,
            'is_portfolio_published'  => true,
            'portfolio_theme'         => $this->theme,
            'portfolio_custom_domain' => $this->customDomain ?: null,
        ]);

        $this->isPublished = true;
        $this->dispatch('showNotification', [
            'type'    => 'success',
            'title'   => 'Published!',
            'message' => 'Your personal portfolio is now live.',
        ]);
    }

    public function unpublish()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update(['is_portfolio_published' => false]);
        $this->isPublished = false;

        $this->dispatch('showNotification', [
            'type' => 'info',
            'title' => 'Unpublished',
            'message' => 'Your portfolio is no longer public.',
        ]);
    }

    public function render()
    {
        return view('livewire.cv-builder.portfolio-publish-modal');
    }
}
