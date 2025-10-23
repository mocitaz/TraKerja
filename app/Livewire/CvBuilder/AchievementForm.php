<?php

namespace App\Livewire\CvBuilder;

use Illuminate\Support\Facades\Auth;
use App\Models\UserAchievement;
use Livewire\Component;

class AchievementForm extends Component
{
    public $achievements;
    public $showModal = false;
    public $editMode = false;
    public $achievementId;
    
    // Form fields
    public $title = '';
    public $issuer = '';
    public $issue_date = '';
    public $credential_id = '';
    public $credential_url = '';
    public $description = '';
    public $display_order = 0;
    
    protected $listeners = ['refreshAchievements' => '$refresh', 'closeAllModals' => 'closeModal'];
    
    public function mount()
    {
        $this->loadAchievements();
    }
    
    public function loadAchievements()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->achievements = $user->achievements()
            ->orderBy('display_order')
            ->get();
    }
    
    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
        $this->display_order = $this->achievements->count();
    }
    
    public function edit($id)
    {
        $achievement = UserAchievement::findOrFail($id);
        
        if ($achievement->user_id !== Auth::id()) {
            abort(403);
        }
        
        $this->achievementId = $achievement->id;
        $this->title = $achievement->title;
        $this->issuer = $achievement->issuer;
        $this->issue_date = $achievement->issue_date?->format('Y-m-d');
        $this->credential_id = $achievement->credential_id ?? '';
        $this->credential_url = $achievement->credential_url ?? '';
        $this->description = $achievement->description ?? '';
        $this->display_order = $achievement->display_order;
        
        $this->editMode = true;
        $this->showModal = true;
    }
    
    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'credential_id' => 'nullable|string|max:255',
            'credential_url' => 'nullable|url|max:500',
            'description' => 'nullable|string',
        ]);
        
        $data = [
            'user_id' => Auth::id(),
            'title' => $this->title,
            'issuer' => $this->issuer,
            'issue_date' => $this->issue_date,
            'credential_id' => $this->credential_id,
            'credential_url' => $this->credential_url,
            'description' => $this->description,
            'display_order' => $this->display_order,
        ];
        
        if ($this->editMode) {
            $achievement = UserAchievement::findOrFail($this->achievementId);
            $achievement->update($data);
            $message = 'Achievement updated successfully!';
        } else {
            UserAchievement::create($data);
            $message = 'Achievement added successfully!';
        }
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Success',
            'message' => $message,
        ]);
        
        $this->closeModal();
        $this->loadAchievements();
    }
    
    public function delete($id)
    {
        $achievement = UserAchievement::findOrFail($id);
        
        if ($achievement->user_id !== Auth::id()) {
            abort(403);
        }
        
        $achievement->delete();
        
        $this->dispatch('showNotification', [
            'type' => 'info',
            'title' => 'Deleted',
            'message' => 'Achievement deleted successfully!',
        ]);
        
        $this->loadAchievements();
    }
    
    public function moveUp($id)
    {
        $achievement = UserAchievement::findOrFail($id);
        if ($achievement->display_order > 0) {
            $swapWith = UserAchievement::where('user_id', Auth::id())
                ->where('display_order', $achievement->display_order - 1)
                ->first();
            
            if ($swapWith) {
                $swapWith->display_order = $achievement->display_order;
                $swapWith->save();
            }
            
            $achievement->display_order -= 1;
            $achievement->save();
        }
        
        $this->loadAchievements();
    }
    
    public function moveDown($id)
    {
        $achievement = UserAchievement::findOrFail($id);
        $maxOrder = $this->achievements->max('display_order');
        
        if ($achievement->display_order < $maxOrder) {
            $swapWith = UserAchievement::where('user_id', Auth::id())
                ->where('display_order', $achievement->display_order + 1)
                ->first();
            
            if ($swapWith) {
                $swapWith->display_order = $achievement->display_order;
                $swapWith->save();
            }
            
            $achievement->display_order += 1;
            $achievement->save();
        }
        
        $this->loadAchievements();
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    private function resetForm()
    {
        $this->achievementId = null;
        $this->title = '';
        $this->issuer = '';
        $this->issue_date = '';
        $this->credential_id = '';
        $this->credential_url = '';
        $this->description = '';
        $this->display_order = 0;
    }
    
    public function render()
    {
        return view('livewire.cv-builder.achievement-form');
    }
}
