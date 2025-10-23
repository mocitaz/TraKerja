<?php

namespace App\Livewire\CvBuilder;

use Illuminate\Support\Facades\Auth;
use App\Models\UserProject;
use Livewire\Component;

class ProjectForm extends Component
{
    public $projects;
    public $showModal = false;
    public $editMode = false;
    public $projectId;
    
    // Form fields
    public $project_name = '';
    public $role = '';
    public $start_date = '';
    public $end_date = '';
    public $is_ongoing = false;
    public $project_url = '';
    public $technologies = '';
    public $description = '';
    public $display_order = 0;
    
    protected $listeners = ['refreshProjects' => '$refresh', 'closeAllModals' => 'closeModal'];
    
    public function mount()
    {
        $this->loadProjects();
    }
    
    public function loadProjects()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->projects = $user->projects()
            ->orderBy('display_order')
            ->get();
    }
    
    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
        $this->display_order = $this->projects->count();
    }
    
    public function edit($id)
    {
        $project = UserProject::findOrFail($id);
        
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }
        
        $this->projectId = $project->id;
        $this->project_name = $project->project_name;
        $this->role = $project->role ?? '';
        $this->start_date = $project->start_date?->format('Y-m-d');
        $this->end_date = $project->end_date?->format('Y-m-d');
        $this->is_ongoing = $project->is_ongoing;
        $this->project_url = $project->project_url ?? '';
        $this->technologies = $project->technologies ?? '';
        $this->description = $project->description ?? '';
        $this->display_order = $project->display_order;
        
        $this->editMode = true;
        $this->showModal = true;
    }
    
    public function save()
    {
        $this->validate([
            'project_name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'project_url' => 'nullable|url|max:500',
            'technologies' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);
        
        $data = [
            'user_id' => Auth::id(),
            'project_name' => $this->project_name,
            'role' => $this->role,
            'start_date' => $this->start_date,
            'end_date' => $this->is_ongoing ? null : $this->end_date,
            'is_ongoing' => $this->is_ongoing,
            'project_url' => $this->project_url,
            'technologies' => $this->technologies,
            'description' => $this->description,
            'display_order' => $this->display_order,
        ];
        
        if ($this->editMode) {
            $project = UserProject::findOrFail($this->projectId);
            $project->update($data);
            $message = 'Project updated successfully!';
        } else {
            UserProject::create($data);
            $message = 'Project added successfully!';
        }
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Success',
            'message' => $message,
        ]);
        
        $this->closeModal();
        $this->loadProjects();
    }
    
    public function delete($id)
    {
        $project = UserProject::findOrFail($id);
        
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }
        
        $project->delete();
        
        $this->dispatch('showNotification', [
            'type' => 'info',
            'title' => 'Deleted',
            'message' => 'Project deleted successfully!',
        ]);
        
        $this->loadProjects();
    }
    
    public function moveUp($id)
    {
        $project = UserProject::findOrFail($id);
        if ($project->display_order > 0) {
            $swapWith = UserProject::where('user_id', Auth::id())
                ->where('display_order', $project->display_order - 1)
                ->first();
            
            if ($swapWith) {
                $swapWith->display_order = $project->display_order;
                $swapWith->save();
            }
            
            $project->display_order -= 1;
            $project->save();
        }
        
        $this->loadProjects();
    }
    
    public function moveDown($id)
    {
        $project = UserProject::findOrFail($id);
        $maxOrder = $this->projects->max('display_order');
        
        if ($project->display_order < $maxOrder) {
            $swapWith = UserProject::where('user_id', Auth::id())
                ->where('display_order', $project->display_order + 1)
                ->first();
            
            if ($swapWith) {
                $swapWith->display_order = $project->display_order;
                $swapWith->save();
            }
            
            $project->display_order += 1;
            $project->save();
        }
        
        $this->loadProjects();
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    private function resetForm()
    {
        $this->projectId = null;
        $this->project_name = '';
        $this->role = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->is_ongoing = false;
        $this->project_url = '';
        $this->technologies = '';
        $this->description = '';
        $this->display_order = 0;
    }
    
    public function render()
    {
        return view('livewire.cv-builder.project-form');
    }
}
