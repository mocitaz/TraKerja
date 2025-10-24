<?php

namespace App\Livewire\CvBuilder;

use Illuminate\Support\Facades\Auth;
use App\Models\UserOrganization;
use Livewire\Component;

class OrganizationForm extends Component
{
    public $organizations;
    public $showModal = false;
    public $editMode = false;
    public $organizationId;
    
    // Form fields
    public $organization_name = '';
    public $position = '';
    public $location = '';
    public $start_date = '';
    public $end_date = '';
    public $is_current = false;
    public $description = '';
    public $display_order = 0;
    
    protected $listeners = ['refreshOrganizations' => '$refresh', 'closeAllModals' => 'closeModal'];
    
    public function mount()
    {
        $this->loadOrganizations();
    }
    
    public function loadOrganizations()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->organizations = $user->organizations()
            ->orderBy('display_order')
            ->get();
    }
    
    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
        $this->display_order = $this->organizations->count();
    }
    
    public function edit($id)
    {
        $organization = UserOrganization::findOrFail($id);
        
        if ($organization->user_id !== Auth::id()) {
            abort(403);
        }
        
        $this->organizationId = $organization->id;
        $this->organization_name = $organization->organization_name;
        $this->position = $organization->position;
        $this->location = $organization->location ?? '';
        $this->start_date = $organization->start_date?->format('Y-m-d');
        $this->end_date = $organization->end_date?->format('Y-m-d');
        $this->is_current = $organization->is_current;
        $this->description = $organization->description ?? '';
        $this->display_order = $organization->display_order;
        
        $this->editMode = true;
        $this->showModal = true;
    }
    
    public function save()
    {
        $this->validate([
            'organization_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string',
        ]);
        
        $data = [
            'user_id' => Auth::id(),
            'organization_name' => $this->organization_name,
            'position' => $this->position,
            'location' => $this->location,
            'start_date' => $this->start_date,
            'end_date' => $this->is_current ? null : $this->end_date,
            'is_current' => $this->is_current,
            'description' => $this->description,
            'display_order' => $this->display_order,
        ];
        
        if ($this->editMode) {
            $organization = UserOrganization::findOrFail($this->organizationId);
            $organization->update($data);
            $message = 'Organization experience updated successfully!';
        } else {
            UserOrganization::create($data);
            $message = 'Organization experience added successfully!';
        }
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Success',
            'message' => $message,
        ]);
        
        $this->closeModal();
        $this->loadOrganizations();
    }
    
    public function delete($id)
    {
        $organization = UserOrganization::findOrFail($id);
        
        if ($organization->user_id !== Auth::id()) {
            abort(403);
        }
        
        $organization->delete();
        
        $this->dispatch('showNotification', [
            'type' => 'info',
            'title' => 'Deleted',
            'message' => 'Organization deleted successfully!',
        ]);
        
        $this->loadOrganizations();
    }
    
    public function moveUp($id)
    {
        $organization = UserOrganization::findOrFail($id);
        if ($organization->display_order > 0) {
            $swapWith = UserOrganization::where('user_id', Auth::id())
                ->where('display_order', $organization->display_order - 1)
                ->first();
            
            if ($swapWith) {
                $swapWith->display_order = $organization->display_order;
                $swapWith->save();
            }
            
            $organization->display_order -= 1;
            $organization->save();
        }
        
        $this->loadOrganizations();
    }
    
    public function moveDown($id)
    {
        $organization = UserOrganization::findOrFail($id);
        $maxOrder = $this->organizations->max('display_order');
        
        if ($organization->display_order < $maxOrder) {
            $swapWith = UserOrganization::where('user_id', Auth::id())
                ->where('display_order', $organization->display_order + 1)
                ->first();
            
            if ($swapWith) {
                $swapWith->display_order = $organization->display_order;
                $swapWith->save();
            }
            
            $organization->display_order += 1;
            $organization->save();
        }
        
        $this->loadOrganizations();
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('reset-body-overflow');
    }
    
    private function resetForm()
    {
        $this->organizationId = null;
        $this->organization_name = '';
        $this->position = '';
        $this->location = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->is_current = false;
        $this->description = '';
        $this->display_order = 0;
    }
    
    public function render()
    {
        return view('livewire.cv-builder.organization-form');
    }
}
