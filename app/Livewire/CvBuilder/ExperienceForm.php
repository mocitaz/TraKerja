<?php

namespace App\Livewire\CvBuilder;

use Illuminate\Support\Facades\Auth;

use App\Models\UserExperience;
use Livewire\Component;

class ExperienceForm extends Component
{
    public $experiences;
    public $showModal = false;
    public $editMode = false;
    public $experienceId;
    
    // Form fields
    public $company_name = '';
    public $position = '';
    public $employment_type = 'Full Time';
    public $location = '';
    public $start_date = '';
    public $end_date = '';
    public $is_current = false;
    public $description = '';
    public $display_order = 0;
    
    public $employmentTypes = [
        'Full Time',
        'Part Time',
        'Contract',
        'Internship',
        'Freelance',
        'Self-employed'
    ];
    
    protected $listeners = ['refreshExperiences' => '$refresh', 'closeAllModals' => 'closeModal'];
    
    public function mount()
    {
        $this->loadExperiences();
    }
    
    public function loadExperiences()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->experiences = $user->experiences()
            ->orderBy('display_order')
            ->get();
    }
    
    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
        $this->display_order = $this->experiences->count();
    }
    
    public function edit($id)
    {
        $experience = UserExperience::findOrFail($id);
        
        // Security check
        if ($experience->user_id !== Auth::id()) {
            abort(403);
        }
        
        $this->experienceId = $experience->id;
        $this->company_name = $experience->company_name;
        $this->position = $experience->position ?? $experience->role; // Fallback for old data
        $this->employment_type = $experience->employment_type ?? 'Full Time';
        $this->location = $experience->location;
        $this->start_date = $experience->start_date?->format('Y-m-d');
        $this->end_date = $experience->end_date?->format('Y-m-d');
        $this->is_current = $experience->is_current;
        $this->description = $experience->description;
        $this->display_order = $experience->display_order;
        
        $this->editMode = true;
        $this->showModal = true;
    }
    
    public function save()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'employment_type' => 'required|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string',
        ]);
        
        $data = [
            'user_id' => Auth::id(),
            'company_name' => $this->company_name,
            'position' => $this->position,
            'employment_type' => $this->employment_type,
            'location' => $this->location,
            'start_date' => $this->start_date,
            'end_date' => $this->is_current ? null : $this->end_date,
            'is_current' => $this->is_current,
            'description' => $this->description,
            'display_order' => $this->display_order,
        ];
        
        if ($this->editMode) {
            $experience = UserExperience::findOrFail($this->experienceId);
            $experience->update($data);
            $message = 'Experience updated successfully!';
        } else {
            UserExperience::create($data);
            $message = 'Experience added successfully!';
        }
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Success',
            'message' => $message,
        ]);
        
        $this->closeModal();
        $this->loadExperiences();
    }
    
    public function delete($id)
    {
        $experience = UserExperience::findOrFail($id);
        
        // Security check
        if ($experience->user_id !== Auth::id()) {
            abort(403);
        }
        
        $experience->delete();
        
        $this->dispatch('showNotification', [
            'type' => 'info',
            'title' => 'Deleted',
            'message' => 'Experience deleted successfully!',
        ]);
        
        $this->loadExperiences();
    }
    
    public function moveUp($id)
    {
        $experience = UserExperience::findOrFail($id);
        if ($experience->display_order > 0) {
            $swapWith = UserExperience::where('user_id', Auth::id())
                ->where('display_order', $experience->display_order - 1)
                ->first();
            
            if ($swapWith) {
                $swapWith->display_order = $experience->display_order;
                $swapWith->save();
            }
            
            $experience->display_order -= 1;
            $experience->save();
        }
        
        $this->loadExperiences();
    }
    
    public function moveDown($id)
    {
        $experience = UserExperience::findOrFail($id);
        $maxOrder = $this->experiences->max('display_order');
        
        if ($experience->display_order < $maxOrder) {
            $swapWith = UserExperience::where('user_id', Auth::id())
                ->where('display_order', $experience->display_order + 1)
                ->first();
            
            if ($swapWith) {
                $swapWith->display_order = $experience->display_order;
                $swapWith->save();
            }
            
            $experience->display_order += 1;
            $experience->save();
        }
        
        $this->loadExperiences();
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    private function resetForm()
    {
        $this->experienceId = null;
        $this->company_name = '';
        $this->position = '';
        $this->employment_type = 'Full Time';
        $this->location = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->is_current = false;
        $this->description = '';
        $this->display_order = 0;
    }
    
    public function render()
    {
        return view('livewire.cv-builder.experience-form');
    }
}
