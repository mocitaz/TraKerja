<?php

namespace App\Livewire\CvBuilder;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PortfolioPublishModal extends Component
{
    public bool $showModal = false;
    public string $slug = '';
    #[Locked] public bool $isPublished = false;
    public string $theme = 'slate';
    protected $listeners = ['openPublishModal' => 'openModal'];

    public function mount(): void
    {
        abort_unless(Auth::check(), 401);
        $user = Auth::user()->fresh();
        $this->slug = $user->portfolio_slug ?? Str::slug($user->name);
        $this->isPublished = (bool) $user->is_portfolio_published;
        $this->theme = in_array($user->portfolio_theme, ['slate', 'dark', 'emerald', 'violet'], true) ? $user->portfolio_theme : 'slate';
    }

    public function openModal(): void
    {
        $this->mount();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void { $this->showModal = false; }

    public function updatedSlug(): void
    {
        $this->slug = Str::slug($this->slug);
        $this->resetValidation('slug');
    }

    private function hasContent($user): bool
    {
        if (filled($user->profile?->bio)) return true;
        foreach (['experiences', 'educations', 'skills', 'organizations', 'achievements', 'projects'] as $relation) {
            if ($user->$relation()->exists()) return true;
        }
        return false;
    }

    public function publish(): void
    {
        $this->resetValidation();
        $this->validate([
            'slug' => 'required|string|alpha_dash:ascii|max:50|unique:users,portfolio_slug,'.Auth::id(),
            'theme' => 'required|in:slate,dark,emerald,violet',
        ], ['slug.unique' => 'This address is already taken. Choose another name.']);
        $user = Auth::user()->fresh();
        if (Setting::isMonetizationEnabled() && !$user->isPremium()) {
            $this->addError('access', 'Publishing requires Premium. You can still preview your site privately.');
            return;
        }
        if (!$this->hasContent($user)) {
            $this->addError('content', 'Add a summary or at least one CV section before publishing.');
            return;
        }
        $wasPublished = $user->is_portfolio_published;
        $user->update(['portfolio_slug' => $this->slug, 'is_portfolio_published' => true, 'portfolio_theme' => $this->theme]);
        $this->isPublished = true;
        $this->dispatch('showNotification', ['type' => 'success', 'title' => $wasPublished ? 'Portfolio updated' : 'Portfolio published', 'message' => __('Your saved CV content is now available at your portfolio address.')]);
    }

    public function unpublish(): void
    {
        Auth::user()->update(['is_portfolio_published' => false]);
        $this->isPublished = false;
        $this->dispatch('showNotification', ['type' => 'info', 'title' => __('Portfolio unpublished'), 'message' => __('Your public address is no longer available. Your CV content is kept.')]);
    }

    public function render()
    {
        $user = Auth::user()->fresh()->load('profile');
        $hasContent = $this->hasContent($user);
        $canPublish = !Setting::isMonetizationEnabled() || $user->isPremium();
        $liveUrl = $user->is_portfolio_published ? route('portfolio.show', ['slug' => $user->portfolio_slug]) : null;
        $proposedUrl = url('/@'.$this->slug);
        return view('livewire.cv-builder.portfolio-publish-modal', compact('user', 'hasContent', 'canPublish', 'liveUrl', 'proposedUrl'));
    }
}
