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

    protected $listeners = ['openPublishModal' => 'openModal'];

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->slug = $user->portfolio_slug ?? Str::slug($user->name);
        $this->isPublished = $user->is_portfolio_published;
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
            'slug' => 'required|string|max:50|unique:users,portfolio_slug,' . Auth::id(),
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'portfolio_slug' => $this->slug,
            'is_portfolio_published' => true,
        ]);

        $this->isPublished = true;
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Published!',
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
