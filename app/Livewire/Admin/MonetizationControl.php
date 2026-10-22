<?php

namespace App\Livewire\Admin;

use App\Models\{Setting, User};
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MonetizationControl extends Component
{
    #[Locked] public $monetizationEnabled = false;
    #[Locked] public $showConfirmation = false;
    #[Locked] public $actionType = null;
    public $premiumPrice;
    #[Locked] public $totalUsers = 0;
    #[Locked] public $premiumUsers = 0;

    public function mount()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->monetizationEnabled = (bool) Setting::get('monetization_enabled', false);
        $this->premiumPrice = Setting::get('premium_price', 9999);
        $this->loadStats();
    }

    public function loadStats()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $members = User::where('role', '!=', 'admin');
        $this->totalUsers = (clone $members)->count();
        $this->premiumUsers = (clone $members)->where('is_premium', true)->where('payment_status', 'paid')
            ->where(fn ($q) => $q->whereNull('premium_until')->orWhere('premium_until', '>', now()))->count();
    }

    public function toggleMonetization(bool $enable)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->monetizationEnabled = (bool) Setting::get('monetization_enabled', false);
        if ($enable === $this->monetizationEnabled) return;
        $this->actionType = $enable ? 'enable' : 'disable';
        $this->showConfirmation = true;
    }

    public function confirmToggle()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        if (!$this->showConfirmation || !in_array($this->actionType, ['enable', 'disable'], true)) return;
        $newState = $this->actionType === 'enable';
        Setting::set('monetization_enabled', $newState);
        $this->monetizationEnabled = $newState;
        $this->cancelToggle();
        Log::info('Monetization access mode changed', ['admin_id' => auth()->id(), 'enabled' => $newState]);
        session()->flash('mode_saved', __('Access mode saved.'));
        $this->loadStats();
    }

    public function cancelToggle()
    {
        $this->actionType = null;
        $this->showConfirmation = false;
    }

    public function updatePremiumPrice()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->validate(['premiumPrice' => 'required|integer|min:0|max:2000000000']);
        Setting::set('premium_price', (int) $this->premiumPrice);
        Log::info('Premium base price changed', ['admin_id' => auth()->id(), 'price' => (int) $this->premiumPrice]);
        session()->flash('price_saved', __('Base checkout price saved.'));
    }

    public function render()
    {
        return view('livewire.admin.monetization-control');
    }
}
