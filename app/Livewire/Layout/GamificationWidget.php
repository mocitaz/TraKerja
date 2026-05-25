<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class GamificationWidget extends Component
{
    public $currentXp = 0;

    public function mount()
    {
        $this->currentXp = Auth::user()->xp ?? 0;
    }

    #[On('job-saved')]
    #[On('status-updated')]
    #[On('xp-updated')]
    #[On('job-deleted')]
    public function updateXp()
    {
        $newXp = Auth::user()->refresh()->xp ?? 0;
        
        if ($newXp > $this->currentXp) {
            $diff = $newXp - $this->currentXp;
            $this->dispatch('showNotification', [
                'type' => 'success',
                'title' => 'XP Gained!',
                'message' => "+{$diff} XP added to your profile.",
                'icon' => 'ph-fill ph-lightning text-amber-400',
                'duration' => 5000
            ]);
        }
        
        $this->currentXp = $newXp;
    }

    public function render()
    {
        return view('livewire.layout.gamification-widget');
    }
}
